<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingConfirmation;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(): JsonResponse
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'pickup_location' => 'required|string|max:255',
                'drop_location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'number_of_persons' => 'required|integer|min:1',
                'contact' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'notes' => 'nullable|string',
            ]);

            $booking = Booking::create([
                'name' => $validatedData['name'],
                'pickup_location' => $validatedData['pickup_location'],
                'drop_location' => $validatedData['drop_location'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'booking_date' => now()->toDateString(),
                'number_of_persons' => $validatedData['number_of_persons'],
                'contact' => $validatedData['contact'],
                'email' => $validatedData['email'],
                'notes' => $validatedData['notes'] ?? null,
            ]);


            // Send to admin - you might want to create a different Mailable for admin notifications

            try {
                Mail::to(env('MAIL_FROM_ADDRESS'))
                    ->send(new BookingConfirmation($booking));
            } catch (Exception $e) {
                Log::error('Email sending failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (QueryException $e) {
            Log::error('Database error while creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking due to database error'
            ], 500);

        } catch (Exception $e) {
            Log::error('Error while creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking'
            ], 500);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'pickup_location' => 'sometimes|required|string|max:255',
            'drop_location' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'number_of_persons' => 'sometimes|required|integer|min:1',
            'contact' => 'sometimes|required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $booking->update($request->only([
            'name',
            'pickup_location',
            'drop_location',
            'start_date',
            'end_date',
            'number_of_persons',
            'contact',
            'notes',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }
}