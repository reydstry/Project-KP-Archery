<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\SessionBooking;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Get today's sessions for coach
     */
    public function sessions(Request $request)
    {
        $date = $request->input('date', today());
        
        $bookings = SessionBooking::with(['member', 'sessionTime'])
            ->whereDate('booked_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booked_date')
            ->get(); // ← TAMBAHKAN INI (hilang di kode kamu)

        return response()->json([
            'data' => [
                'date' => $date,
                'bookings' => $bookings,
            ],
        ]);
    }

    /**
     * Validate attendance (hadir/tidak hadir)
     */
    public function validateAttendance(Request $request)
    {
        $data = $request->validate([
            'booking_id' => ['required', 'exists:session_bookings,id'],
            'status' => ['required', 'in:present,absent'],
            'notes' => ['nullable', 'string'],
        ]);

        $booking = SessionBooking::findOrFail($data['booking_id']);
        
        // Create attendance record
        $attendance = Attendance::create([
            'member_id' => $booking->member_id,
            'session_time_id' => $booking->session_time_id,
            'date' => $booking->booked_date,
            'status' => $data['status'],
            'validated_by' => $request->user()->id,
            'notes' => $data['notes'] ?? null,
        ]);

        // Update booking status
        $booking->update([
            'status' => $data['status'] === 'present' ? 'completed' : 'cancelled',
        ]);

        // If present, decrement member package session
        if ($data['status'] === 'present') {
            $memberPackage = $booking->member->activePackage;
            if ($memberPackage) {
                $memberPackage->increment('used_sessions');
            }
        }

        return response()->json([
            'message' => 'Kehadiran berhasil divalidasi',
            'data' => $attendance->load('member'),
        ]);
    }

    /**
     * Coach book member to session
     */
    public function bookMember(Request $request)
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'session_time_id' => ['required', 'exists:session_times,id'],
            'booked_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);

        $booking = SessionBooking::create([
            'member_id' => $data['member_id'],
            'session_time_id' => $data['session_time_id'],
            'booked_date' => $data['booked_date'],
            'booked_by' => $request->user()->id,
            'status' => 'confirmed', // coach langsung confirmed
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Member berhasil dibooking',
            'data' => $booking->load('member', 'sessionTime'),
        ], 201);
    }
}