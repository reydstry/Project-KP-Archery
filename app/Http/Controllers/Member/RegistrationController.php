<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Enums\StatusMember;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Register self as member
     */
    public function registerSelf(Request $request)
    {
        $user = $request->user();

        // Check if user already registered as self
        $existingSelf = Member::where('user_id', $user->id)
            ->where('is_self', true)
            ->first();

        if ($existingSelf) {
            return response()->json([
                'message' => 'Anda sudah terdaftar sebagai member',
                'data' => $existingSelf,
            ], 422);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'registered_by' => $user->id,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'is_self' => true,
            'status' => StatusMember::STATUS_PENDING,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Pendaftaran member berhasil. Menunggu verifikasi admin.',
            'data' => $member->load(['user', 'registeredBy']),
        ], 201);
    }

    /**
     * Register child as member
     */
    public function registerChild(Request $request)
    {
        $user = $request->user();

        // Verify user has registered self first
        $userMember = Member::where('user_id', $user->id)
            ->where('is_self', true)
            ->first();

        if (!$userMember) {
            return response()->json([
                'message' => 'Anda harus mendaftar sebagai member terlebih dahulu sebelum mendaftarkan anak',
            ], 422);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'registered_by' => $user->id,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'is_self' => false,
            'status' => StatusMember::STATUS_PENDING,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Pendaftaran anak berhasil. Menunggu verifikasi admin.',
            'data' => $member->load(['user', 'registeredBy']),
        ], 201);
    }

    /**
     * Get user's registered members
     */
    public function myMembers(Request $request)
    {
        $user = $request->user();

        $members = Member::where('user_id', $user->id)
            ->with(['user', 'registeredBy'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data member berhasil diambil',
            'data' => $members,
        ]);
    }

    /**
     * Get pending members (for admin approval list)
     */
    public function pendingMembers(Request $request)
    {
        $members = Member::pending()
            ->with(['user', 'registeredBy'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data member pending berhasil diambil',
            'data' => $members,
        ]);
    }
}
