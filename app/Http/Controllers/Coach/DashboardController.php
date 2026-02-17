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

        $now = now();
        $today = $now->toDateString();

        $shouldCloseToday = $now->hour > TrainingSession::AUTO_CLOSE_HOUR
            || ($now->hour === TrainingSession::AUTO_CLOSE_HOUR && $now->minute >= TrainingSession::AUTO_CLOSE_MINUTE);

        TrainingSession::query()
            ->where('status', 'open')
            ->where('date', $shouldCloseToday ? '<=' : '<', $today)
            ->update(['status' => 'closed']);

        $assignedToCoach = fn ($query) => $query
            ->whereHas('slots.coaches', fn ($q) => $q->where('coaches.id', $coach->id));

        $todaySessions = TrainingSession::query()
            ->with(['slots.sessionTime', 'slots.coaches', 'attendances.member'])
            ->whereDate('date', $today)
            ->where($assignedToCoach)
            ->get();

        $todaySlots = $todaySessions
            ->flatMap(fn (TrainingSession $session) => $session->slots
                ->filter(fn ($slot) => $slot->coaches->contains('id', $coach->id))
                ->sortBy('session_time_id')
                ->values()
                ->map(fn ($slot) => [
                    'id' => $slot->id,
                    'training_session_id' => $slot->training_session_id,
                    'date' => $session->date,
                    'status' => $session->status?->value,
                    'max_participants' => $slot->max_participants,
                    'capacity' => $slot->max_participants,
                    'total_attendances' => $session->attendances->count(),
                    'coaches' => $slot->coaches->map(fn ($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                    ])->values(),
                    'members' => $session->attendances
                        ? $session->attendances->map(fn ($attendance) => [
                            'id' => $attendance->member?->id,
                            'name' => $attendance->member?->name,
                        ])->filter(fn ($m) => $m['id'])->values()
                        : [],
                    'session_time' => [
                        'id' => $slot->sessionTime?->id,
                        'session_name' => $slot->sessionTime?->name,
                        'start_time' => $slot->sessionTime?->start_time,
                        'end_time' => $slot->sessionTime?->end_time,
                    ],
                ]))
            ->values();

        $upcomingCount = TrainingSession::query()
            ->whereDate('date', '>=', $today)
            ->where($assignedToCoach)
            ->count();

        $totalCount = TrainingSession::query()
            ->where($assignedToCoach)
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
