<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Get list of members with their active packages for coach booking
     */
    public function index(Request $request)
    {
        $query = Member::with(['memberPackages' => function ($q) {
            $q->active()->with('package');
        }]);

        // Filter by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by is_active
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $members = $query->orderBy('name')->get();

        // Transform to include only members with active packages
        $membersWithPackages = $members->map(function ($member) {
            $activePackages = $member->memberPackages->filter(function ($mp) {
                return $mp->is_active 
                    && $mp->end_date->isFuture() 
                    && $mp->used_sessions < $mp->total_sessions;
            })->values();

            // Set member status to inactive if no active packages
            $effectiveStatus = $member->status;
            if ($activePackages->isEmpty() && in_array($effectiveStatus, ['active', 'pending'])) {
                $effectiveStatus = 'inactive';
            }

            return [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'status' => $effectiveStatus,
                'is_active' => $member->is_active,
                'active_packages' => $activePackages->map(function ($mp) {
                    return [
                        'id' => $mp->id,
                        'package_name' => $mp->package->name ?? 'Unknown',
                        'total_sessions' => $mp->total_sessions,
                        'used_sessions' => $mp->used_sessions,
                        'remaining_sessions' => $mp->total_sessions - $mp->used_sessions,
                        'start_date' => $mp->start_date->toDateString(),
                        'end_date' => $mp->end_date->toDateString(),
                    ];
                }),
            ];
        });

        // Optionally filter to only members with active packages
        if ($request->boolean('has_active_package')) {
            $membersWithPackages = $membersWithPackages->filter(function ($m) {
                return count($m['active_packages']) > 0;
            })->values();
        }

        return response()->json([
            'data' => $membersWithPackages,
        ]);
    }
}
