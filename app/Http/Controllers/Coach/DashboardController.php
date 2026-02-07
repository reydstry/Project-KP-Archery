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

        $todaySessions = TrainingSession::query()
            ->where('coach_id', $coach->id)
            ->whereDate('date', $today)
            ->orderBy('session_time_id')
            ->get()
            ->map(fn (TrainingSession $session) => [
                'id' => $session->id,
                'date' => $session->date,
                'status' => $session->status?->value,
                'max_participants' => $session->max_participants,
                'session_time_id' => $session->session_time_id,
            ]);

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
                'today_sessions' => $todaySessions->count(),
                'upcoming_sessions' => $upcomingCount,
                'total_sessions' => $totalCount,
            ],
            'today_sessions' => $todaySessions,
        ]);
    }
}
