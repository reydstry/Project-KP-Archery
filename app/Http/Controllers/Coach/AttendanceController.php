<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SessionBooking;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Get all bookings for a training session with attendance status
     */
    public function getSessionBookings(TrainingSession $trainingSession)
    {
        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only view your own training sessions'
            ], 403);
        }

        $bookings = SessionBooking::with([
            'memberPackage.member',
            'attendance'
        ])
            ->where('training_session_id', $trainingSession->id)
            ->where('status', 'confirmed')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'member_name' => $booking->memberPackage->member->name,
                    'member_id' => $booking->memberPackage->member->id,
                    'has_attendance' => !is_null($booking->attendance),
                    'attendance_status' => $booking->attendance?->status,
                    'validated_at' => $booking->attendance?->validated_at,
                    'notes' => $booking->attendance?->notes,
                ];
            });

        return response()->json([
            'session' => [
                'id' => $trainingSession->id,
                'date' => $trainingSession->date,
                'session_time' => $trainingSession->sessionTime->name ?? null,
                'status' => $trainingSession->status,
            ],
            'bookings' => $bookings,
            'total_bookings' => $bookings->count(),
            'attended' => $bookings->where('attendance_status', 'present')->count(),
            'absent' => $bookings->where('attendance_status', 'absent')->count(),
            'not_validated' => $bookings->where('has_attendance', false)->count(),
        ]);
    }

    /**
     * Validate attendance for a booking
     */
    public function validateAttendance(SessionBooking $sessionBooking, Request $request)
    {
        // Get training session
        $trainingSession = $sessionBooking->trainingSession;

        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only validate attendance for your own training sessions'
            ], 403);
        }

        // Verify booking is confirmed
        if ($sessionBooking->status !== 'confirmed') {
            return response()->json([
                'message' => 'Can only validate attendance for confirmed bookings'
            ], 422);
        }

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if attendance already exists
        $attendance = Attendance::where('session_booking_id', $sessionBooking->id)->first();

        if ($attendance) {
            return response()->json([
                'message' => 'Attendance already validated for this booking'
            ], 422);
        }

        // Create attendance record
        $attendance = Attendance::create([
            'session_booking_id' => $sessionBooking->id,
            'status' => $validated['status'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Attendance validated successfully',
            'attendance' => [
                'id' => $attendance->id,
                'booking_id' => $sessionBooking->id,
                'member_name' => $sessionBooking->memberPackage->member->name,
                'status' => $attendance->status,
                'validated_at' => $attendance->validated_at,
                'notes' => $attendance->notes,
            ]
        ], 201);
    }

    /**
     * Update attendance status
     */
    public function update(SessionBooking $sessionBooking, Request $request)
    {
        // Get training session
        $trainingSession = $sessionBooking->trainingSession;

        // Verify coach owns this session
        $coach = auth()->user()->coach;
        if (!$coach || $trainingSession->coach_id !== $coach->id) {
            return response()->json([
                'message' => 'You can only update attendance for your own training sessions'
            ], 403);
        }

        // Get attendance
        $attendance = Attendance::where('session_booking_id', $sessionBooking->id)->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'Attendance not found for this booking'
            ], 404);
        }

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string|max:500',
        ]);

        // Update attendance
        $attendance->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $attendance->notes,
            'validated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'attendance' => [
                'id' => $attendance->id,
                'booking_id' => $sessionBooking->id,
                'member_name' => $sessionBooking->memberPackage->member->name,
                'status' => $attendance->status,
                'validated_at' => $attendance->validated_at,
                'notes' => $attendance->notes,
            ]
        ]);
    }
}
