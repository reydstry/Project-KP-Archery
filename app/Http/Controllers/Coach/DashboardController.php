<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $coach = Coach::where('user_id', $user->id)->first();

        if (!$coach) {
            return response()->json([
                'coach' => null,
                'statistics' => [
                    'today_sessions' => 0,
                    'upcoming_sessions' => 0,
                    'total_sessions' => 0,
                ],
                'today_sessions' => [],
            ]);
        }

        $today = now()->toDateString();

        $todaySession = TrainingSession::query()
            ->with(['slots.sessionTime', 'slots.confirmedBookings'])
            ->where('coach_id', $coach->id)
            ->whereDate('date', $today)
            ->first();

        $todaySlots = $todaySession
            ? $todaySession->slots
                ->sortBy('session_time_id')
                ->values()
                ->map(fn ($slot) => [
                    'id' => $slot->id,
                    'training_session_id' => $slot->training_session_id,
                    'date' => $todaySession->date,
                    'status' => $todaySession->status?->value,
                    'max_participants' => $slot->max_participants,
                    'capacity' => $slot->max_participants,
                    'total_bookings' => $slot->confirmedBookings->count(),
                    'session_time' => [
                        'id' => $slot->sessionTime?->id,
                        'session_name' => $slot->sessionTime?->name,
                        'start_time' => $slot->sessionTime?->start_time,
                        'end_time' => $slot->sessionTime?->end_time,
                    ],
                ])
            : collect();

        $upcomingCount = TrainingSession::query()
            ->where('coach_id', $coach->id)
            ->whereDate('date', '>=', $today)
            ->count();

        $totalCount = TrainingSession::query()
            ->where('coach_id', $coach->id)
            ->count();

        return response()->json([
            'coach' => [
                'id' => $coach->id,
                'name' => $coach->name,
                'phone' => $coach->phone,
            ],
            'statistics' => [
                'today_sessions' => $todaySlots->count(),
                'upcoming_sessions' => $upcomingCount,
                'total_sessions' => $totalCount,
            ],
            'today_sessions' => $todaySlots,
        ]);
    }
}
