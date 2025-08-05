<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Str;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
public function register(Request $request): JsonResponse
{
    $response = null;
    try {
        // Validation (optional but recommended)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $response = response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        } else {
            // User creation
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $response = response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user
                ]
            ], 201);
        }
    } catch (QueryException $qe) {
        Log::error('Database Error: ' . $qe->getMessage());

        $response = response()->json([
            'success' => false,
            'message' => 'Database error occurred.',
        ], 500);
    } catch (\Exception $e) {
        Log::error('General Error: ' . $e->getMessage());

        $response = response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred.',
        ], 500);
    }
    return $response;
}

    /**
     * Login user and create token.
     */
    public function login(Request $request): JsonResponse
    {
        Log::info(("ok"));
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Logout user (Revoke the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            // Generate 6 digit OTP
            $otp = rand(100000, 999999);
            
            // Create or update password reset record
            PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $otp,
                    'created_at' => now()
                ]
            );

            // Send OTP to email
            Mail::to($request->email)->send(new SendOtpMail($otp));

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address'
            ]);

        } catch (\Exception $e) {
            Log::error('Forgot Password Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process forgot password request'
            ], 500);
        }
    }

    /**verifyOtp
     * Verify OTP
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:password_resets,email',
                'otp' => 'required|numeric|digits:6'
            ]);

            $otpRecord = PasswordReset::where('email', $request->email)
                ->where('token', $request->otp)
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 422);
            }

            // Check if OTP is expired (15 minutes validity)
            if (now()->diffInMinutes($otpRecord->created_at) > 15) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired'
                ], 422);
            }

            // Generate a verification token for password reset
            $verificationToken = Str::random(60);
            $otpRecord->update(['verification_token' => $verificationToken]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'data' => [
                    'verification_token' => $verificationToken
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Verify OTP Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify OTP'
            ], 500);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:6|confirmed',
                'verification_token' => 'required|string'
            ]);

            // Verify token
            $otpRecord = PasswordReset::where('email', $request->email)
                ->where('verification_token', $request->verification_token)
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification token'
                ], 422);
            }

            // Update user password
            User::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

            // Delete the reset record
            PasswordReset::where('email', $request->email)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Reset Password Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password'
            ], 500);
        }
    }
}