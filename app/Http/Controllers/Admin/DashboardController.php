<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Coach;
use App\Models\Member;
use App\Models\News;
use App\Models\Package;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $statistics = [
            'pending_members' => Member::pending()->count(),
            'active_members' => Member::active()->count(),
            'total_members' => Member::count(),
            'total_coaches' => Coach::count(),
            'total_packages' => Package::count(),
            'total_news' => News::count(),
            'total_achievements' => Achievement::count(),
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
        ]);
    }
}
