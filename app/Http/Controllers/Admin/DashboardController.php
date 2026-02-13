<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Coach;
use App\Models\Member;
use App\Models\News;
use App\Models\Package;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $today = $now->toDateString();

        $shouldCloseToday = $now->hour > TrainingSession::AUTO_CLOSE_HOUR
            || ($now->hour === TrainingSession::AUTO_CLOSE_HOUR && $now->minute >= TrainingSession::AUTO_CLOSE_MINUTE);

        TrainingSession::query()
            ->where('status', 'open')
            ->where('date', $shouldCloseToday ? '<=' : '<', $today)
            ->update(['status' => 'closed']);

        $todaySessions = TrainingSession::query()
            ->with(['slots.sessionTime', 'slots.confirmedBookings.memberPackage.member', 'slots.coaches'])
            ->whereDate('date', $today)
            ->get();

        $todaySlots = $todaySessions
            ->flatMap(fn (TrainingSession $session) => $session->slots
                ->sortBy('session_time_id')
                ->values()
                ->map(fn ($slot) => [
                    'id' => $slot->id,
                    'training_session_id' => $slot->training_session_id,
                    'date' => $session->date,
                    'status' => $session->status?->value,
                    'max_participants' => $slot->max_participants,
                    'capacity' => $slot->max_participants,
                    'total_bookings' => $slot->confirmedBookings->count(),
                    'coaches' => $slot->coaches
                        ? $slot->coaches->map(fn ($c) => [
                            'id' => $c->id,
                            'name' => $c->name,
                        ])->values()
                        : [],
                    'members' => $slot->confirmedBookings
                        ? $slot->confirmedBookings->map(fn ($booking) => [
                            'id' => $booking->memberPackage?->member?->id,
                            'name' => $booking->memberPackage?->member?->name,
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
            ->count();

        $totalCount = TrainingSession::query()->count();

        $statistics = [
            'pending_members' => Member::pending()->count(),
            'active_members' => Member::active()->count(),
            'total_members' => Member::count(),
            'total_coaches' => Coach::count(),
            'total_packages' => Package::count(),
            'total_news' => News::count(),
            'total_achievements' => Achievement::count(),
            'today_sessions' => $todaySlots->count(),
            'upcoming_sessions' => $upcomingCount,
            'total_sessions' => $totalCount,
        ];

        $recentPendingMembers = Member::pending()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'name', 'phone', 'status', 'created_at'])
            ->map(fn (Member $member) => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'status' => $member->status,
                'created_at' => $member->created_at,
            ]);

        return response()->json([
            'statistics' => $statistics,
            'recent' => [
                'pending_members' => $recentPendingMembers,
            ],
            'today_sessions' => $todaySlots,
        ]);
    }
}
