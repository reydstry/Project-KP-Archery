<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\SessionBooking;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Get member dashboard (untuk user role member)
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $members = $user->members()->with('activePackage')->get();

        return response()->json([
            'data' => [
                'user' => $user,
                'members' => $members,
            ],
        ]);
    }

    /**
     * Register child as member
     */
    public function registerChild(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $member = Member::create([
            'user_id' => $request->user()->id,
            'registered_by' => $request->user()->id,
            'name' => $data['name'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_self' => false, // ini anak, bukan diri sendiri
        ]);

        return response()->json([
            'message' => 'Anak berhasil didaftarkan sebagai member',
            'data' => $member,
        ], 201);
    }

    /**
     * Register self as member
     */
    public function registerSelf(Request $request)
    {
        $data = $request->validate([
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female'],
            'address' => ['nullable', 'string'],
        ]);

        $user = $request->user();

        $member = Member::create([
            'user_id' => $user->id,
            'registered_by' => $user->id,
            'name' => $user->name,
            'birth_date' => $data['birth_date'] ?? null,
            'gender' => $data['gender'] ?? null,
            'phone' => $user->phone,
            'address' => $data['address'] ?? null,
            'is_self' => true, // daftar sendiri
        ]);

        return response()->json([
            'message' => 'Berhasil mendaftar sebagai member',
            'data' => $member,
        ], 201);
    }

    /**
     * Get my members (anak/diri sendiri)
     */
    public function myMembers(Request $request)
    {
        $members = $request->user()->members()->with('activePackage')->get();

        return response()->json([
            'data' => $members,
        ]);
    }

    /**
     * Book session for member
     */
    public function bookSession(Request $request)
    {
        $data = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'session_time_id' => ['required', 'exists:session_times,id'],
            'booked_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);

        // Verify member belongs to user
        $member = Member::where('id', $data['member_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Check if member has active package
        if (!$member->activePackage || !$member->activePackage->hasRemainingSessions()) {
            return response()->json([
                'message' => 'Member tidak memiliki paket aktif atau jatah sudah habis',
            ], 400);
        }

        $booking = SessionBooking::create([
            'member_id' => $data['member_id'],
            'session_time_id' => $data['session_time_id'],
            'booked_date' => $data['booked_date'],
            'booked_by' => $request->user()->id,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Booking berhasil dibuat',
            'data' => $booking->load('member', 'sessionTime'),
        ], 201);
    }
}
