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
            ->get();

        return response()->json([
            'message' => 'Data member packages berhasil diambil',
            'data' => $memberPackages,
        ]);
    }

    /**
     * Assign package to member
     */
    public function assignPackage(Request $request, Member $member)
    {
        if (!$member->is_active || $member->status === StatusMember::STATUS_INACTIVE->value) {
            return response()->json([
                'message' => 'Member is inactive',
            ], 422);
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
        ]);

        // Get package details
        $package = Package::findOrFail($validated['package_id']);

        if (property_exists($package, 'is_active') && !$package->is_active) {
            return response()->json([
                'message' => 'Package is inactive',
            ], 422);
        }

        // Calculate end date
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($package->duration_days);

        DB::beginTransaction();
        try {
            $memberPackage = MemberPackage::where('member_id', $member->id)
                ->latest('id')
                ->first();

            $isCreate = false;
            if (!$memberPackage) {
                $memberPackage = new MemberPackage();
                $memberPackage->member_id = $member->id;
                $isCreate = true;
            }

            $memberPackage->package_id = $package->id;
            $memberPackage->total_sessions = $package->session_count;
            $memberPackage->used_sessions = 0;
            $memberPackage->start_date = $startDate;
            $memberPackage->end_date = $endDate;
            $memberPackage->is_active = true;
            $memberPackage->validated_by = auth()->id();
            $memberPackage->validated_at = now();
            $memberPackage->save();

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
            ], $isCreate ? 201 : 200);
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
        $memberPackage->load(['member', 'package', 'validator']);
        return response()->json($memberPackage);
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
