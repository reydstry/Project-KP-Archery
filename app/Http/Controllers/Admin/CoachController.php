<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRoles;
use App\Models\User;
use App\Services\Admin\CoachManagementService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoachController extends Controller
{
    public function __construct(
        private readonly CoachManagementService $coachManagementService,
    ) {
    }

    /**
     * Display a listing of coaches.
     */
    public function index()
    {
        return response()->json($this->coachManagementService->list());
    }

    /**
     * Store a newly created coach.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'],
            ],
            [
                'name.required' => 'Nama coach wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        $coach = $this->coachManagementService->create($data);

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
        $result = $this->coachManagementService->detail($coach);

        if ($result === null) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        return response()->json($result);
    }

    /**
     * Update the specified coach.
     */
    public function update(Request $request, User $coach)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($coach->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'],
            ],
            [
                'name.required' => 'Nama coach wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            ]
        );

        $result = $this->coachManagementService->update($coach, $data);

        if ($result === null) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        return response()->json($result);
    }

    /**
     * Remove the specified coach.
     */
    public function destroy(User $coach)
    {
        $result = $this->coachManagementService->delete($coach);

        if ($result === null) {
            return response()->json([
                'message' => 'User bukan coach',
            ], 404);
        }

        return response()->json($result);
    }
}
