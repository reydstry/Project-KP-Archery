<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberBookingController extends Controller
{
    /**
     * List members with their active packages for booking.
     */
    public function index(Request $request)
    {
        $query = Member::with(['memberPackages' => function ($q) {
            $q->active()->with('package');
        }]);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $members = $query->orderBy('name')->get();

        $data = $members->map(function ($member) {
            $activePackages = $member->memberPackages->filter(function ($mp) {
                $packageOk = true;
                if ($mp->relationLoaded('package') && $mp->package) {
                    $packageOk = (bool) $mp->package->is_active;
                }

                return (bool) $mp->is_active
                    && $packageOk
                    && $mp->end_date
                    && $mp->end_date->isFuture()
                    && $mp->used_sessions < $mp->total_sessions;
            })->values();

            return [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'status' => $member->status,
                'is_active' => $member->is_active,
                'active_packages' => $activePackages->map(function ($mp) {
                    return [
                        'id' => $mp->id,
                        'package_name' => $mp->package->name ?? 'Unknown',
                        'total_sessions' => $mp->total_sessions,
                        'used_sessions' => $mp->used_sessions,
                        'remaining_sessions' => $mp->total_sessions - $mp->used_sessions,
                        'start_date' => $mp->start_date?->toDateString(),
                        'end_date' => $mp->end_date?->toDateString(),
                    ];
                }),
            ];
        })->values();

        if ($request->boolean('has_active_package')) {
            $data = $data->filter(function ($m) {
                return count($m['active_packages']) > 0;
            })->values();
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
