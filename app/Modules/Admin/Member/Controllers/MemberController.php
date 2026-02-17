<?php

namespace App\Modules\Admin\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Modules\Admin\Member\Requests\StoreMemberRequest;
use App\Modules\Admin\Member\Requests\UpdateMemberRequest;
use App\Modules\Admin\Member\Services\MemberManagementService;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberManagementService $memberManagementService,
    ) {
    }

    public function index()
    {
        return response()->json($this->memberManagementService->list());
    }

    public function store(StoreMemberRequest $request)
    {
        $member = $this->memberManagementService->create($request->validated(), auth()->id());

        return response()->json([
            'message' => 'Member berhasil dibuat',
            'data' => $member->load(['user', 'registeredBy']),
        ], 201);
    }

    public function show(Member $member)
    {
        return response()->json($this->memberManagementService->detail($member));
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        return response()->json($this->memberManagementService->update($member, $request->validated()));
    }

    public function destroy(Member $member)
    {
        return response()->json($this->memberManagementService->deactivate($member));
    }

    public function restore($id)
    {
        return response()->json($this->memberManagementService->restore((int) $id));
    }
}
