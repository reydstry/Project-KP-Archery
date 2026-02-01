<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CoachController extends Controller
{
    /**
     * Display a listing of coaches.
     */
    public function index()
    {
        $coaches = User::where('role', UserRoles::COACH)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data coaches berhasil diambil',
            'data' => $coaches,
        ]);
    }

    /**
     * Store a newly created coach.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $coach = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => UserRoles::COACH,
        ]);

        return response()->json([
            'message' => 'Coach berhasil dibuat',
            'data' => $coach,
        ], 201);
    }

    /**
     * Display the specified coach.
     */
    public function show(User $coach)
    {
        // Pastikan user yang diambil adalah coach
        if ($coach->role !== UserRoles::COACH) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        return response()->json([
            'message' => 'Data coach berhasil diambil',
            'data' => $coach,
        ]);
    }

    /**
     * Update the specified coach.
     */
    public function update(Request $request, User $coach)
    {
        // Pastikan user yang diupdate adalah coach
        if ($coach->role !== UserRoles::COACH) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($coach->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        // Update password jika diisi
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $coach->update($updateData);

        return response()->json([
            'message' => 'Coach berhasil diupdate',
            'data' => $coach->fresh(),
        ]);
    }

    /**
     * Remove the specified coach.
     */
    public function destroy(User $coach)
    {
        // Pastikan user yang dihapus adalah coach
        if ($coach->role !== UserRoles::COACH) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        $coach->delete();

        return response()->json([
            'message' => 'Coach berhasil dihapus',
        ]);
    }
}
