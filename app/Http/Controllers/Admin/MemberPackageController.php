<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Services\Admin\PackageManagementService;
use Illuminate\Http\Request;

class MemberPackageController extends Controller
{
    public function __construct(
        private readonly PackageManagementService $packageManagementService,
    ) {
    }

    /**
     * Display a listing of member packages
     */
    public function index()
    {
        return response()->json($this->packageManagementService->listMemberPackages());
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

        $result = $this->packageManagementService->assignPackageToMember(
            member: $member,
            packageId: (int) $validated['package_id'],
            startDate: $validated['start_date'],
            validatorId: auth()->id(),
        );

        return response()->json($result['body'], $result['status']);
    }

    /**
     * Display the specified member package
     */
    public function show(MemberPackage $memberPackage)
    {
        return response()->json($this->packageManagementService->showMemberPackage($memberPackage));
    }

    /**
     * Get member packages for specific member
     */
    public function getMemberPackages(Member $member)
    {
        return response()->json($this->packageManagementService->listPackagesByMember($member));
    }
}
