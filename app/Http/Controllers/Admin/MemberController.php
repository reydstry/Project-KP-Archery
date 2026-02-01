<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index()
    {
        $members = Member::with(['user', 'registeredBy'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data members berhasil diambil',
            'data' => $members,
        ]);
    }

    /**
     * Store a newly created member.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'registered_by' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_self' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $member = Member::create($data);

        return response()->json([
            'message' => 'Member berhasil dibuat',
            'data' => $member->load(['user', 'registeredBy']),
        ], 201);
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load(['user', 'registeredBy']);

        return response()->json([
            'message' => 'Data member berhasil diambil',
            'data' => $member,
        ]);
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_self' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $member->update($data);

        return response()->json([
            'message' => 'Member berhasil diupdate',
            'data' => $member->fresh(['user', 'registeredBy']),
        ]);
    }

    /**
     * Soft delete member (set is_active = false).
     */
    public function destroy(Member $member)
    {
        $member->update(['is_active' => false]);

        return response()->json([
            'message' => 'Member berhasil dinonaktifkan',
        ]);
    }

    /**
     * Restore inactive member (set is_active = true).
     */
    public function restore($id)
    {
        $member = Member::findOrFail($id);
        $member->update(['is_active' => true]);

        return response()->json([
            'message' => 'Member berhasil diaktifkan kembali',
            'data' => $member->fresh(['user', 'registeredBy']),
        ]);
    }
}
