<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPackage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard - overview
     */
    public function dashboard()
    {
        return response()->json([
            'message' => 'Admin dashboard',
            'data' => [
                'total_members' => Member::count(),
                'active_members' => Member::where('is_active', true)->count(),
                'pending_packages' => MemberPackage::where('is_active', false)->count(),
            ],
        ]);
    }

    /**
     * Get specific member dashboard
     */
    public function memberDashboard(Member $member)
    {
        $member->load(['activePackage', 'attendances' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return response()->json([
            'data' => [
                'member' => $member,
                'active_package' => $member->activePackage,
                'recent_attendances' => $member->attendances,
            ],
        ]);
    }

    /**
     * Validate member package (aktivasi paket setelah pembayaran)
     */
    public function validatePackage(Request $request, MemberPackage $memberPackage)
    {
        $memberPackage->update([
            'is_active' => true,
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Paket berhasil divalidasi',
            'data' => $memberPackage->load('member', 'package'),
        ]);
    }
}