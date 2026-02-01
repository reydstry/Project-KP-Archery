<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusMember;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberPackageController extends Controller
{
    /**
     * Display a listing of member packages
     */
    public function index()
    {
        $memberPackages = MemberPackage::with(['member', 'package', 'validator'])
            ->latest()
            ->paginate(15);

        return response()->json($memberPackages);
    }

    /**
     * Assign package to member
     */
    public function assignPackage(Request $request, Member $member)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
        ]);

        // Get package details
        $package = Package::findOrFail($validated['package_id']);

        // Calculate end date
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($package->duration_days);

        DB::beginTransaction();
        try {
            // Create member package
            $memberPackage = MemberPackage::create([
                'member_id' => $member->id,
                'package_id' => $package->id,
                'total_sessions' => $package->session_count,
                'used_sessions' => 0,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
                'validated_by' => auth()->id(),
                'validated_at' => now(),
            ]);

            // Update member status from pending to active
            if ($member->status === StatusMember::STATUS_PENDING->value) {
                $member->update([
                    'status' => StatusMember::STATUS_ACTIVE->value,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Package assigned successfully',
                'data' => $memberPackage->load(['member', 'package', 'validator']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to assign package',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified member package
     */
    public function show(MemberPackage $memberPackage)
    {
        return response()->json($memberPackage->load(['member', 'package', 'validator']));
    }

    /**
     * Get member packages for specific member
     */
    public function getMemberPackages(Member $member)
    {
        $packages = MemberPackage::with(['package', 'validator'])
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        return response()->json($packages);
    }
}
