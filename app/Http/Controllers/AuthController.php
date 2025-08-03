<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

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
}