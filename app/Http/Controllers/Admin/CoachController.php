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

        // Calculate teaching statistics
        $stats = $this->calculateCoachStats($coach);

        return response()->json([
            'message' => 'Data coach berhasil diambil',
            'data' => $coach,
            'statistics' => $stats,
        ]);
    }

    /**
     * Calculate coach teaching statistics
     */
    private function calculateCoachStats(User $coach)
    {
        $now = now();
        
        // Get coach profile and all slots they taught
        $coachProfile = \App\Models\Coach::where('user_id', $coach->id)->first();
        
        if (!$coachProfile) {
            return [
                'teaching_count_this_week' => 0,
                'teaching_count_this_month' => 0,
                'teaching_count_this_year' => 0,
                'week_streak' => 0,
                'total_sessions_taught' => 0,
            ];
        }

        // Get all training session slots where this coach was assigned
        $taughtSlots = \App\Models\TrainingSessionSlot::whereHas('coaches', function ($query) use ($coachProfile) {
            $query->where('coach_id', $coachProfile->id);
        })
        ->with(['trainingSession'])
        ->get()
        ->filter(function ($slot) {
            // Only count sessions that have occurred (past or today)
            $sessionDate = $slot->trainingSession?->date;
            return $sessionDate && $sessionDate->lte(now());
        });

        // Count sessions this week (Monday to Sunday)
        $thisWeekStart = $now->copy()->startOfWeek();
        $thisWeekEnd = $now->copy()->endOfWeek();
        $thisWeekCount = $taughtSlots->filter(function ($slot) use ($thisWeekStart, $thisWeekEnd) {
            $sessionDate = $slot->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisWeekStart, $thisWeekEnd);
        })->count();

        // Count sessions this month
        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd = $now->copy()->endOfMonth();
        $thisMonthCount = $taughtSlots->filter(function ($slot) use ($thisMonthStart, $thisMonthEnd) {
            $sessionDate = $slot->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisMonthStart, $thisMonthEnd);
        })->count();

        // Count sessions this year
        $thisYearStart = $now->copy()->startOfYear();
        $thisYearEnd = $now->copy()->endOfYear();
        $thisYearCount = $taughtSlots->filter(function ($slot) use ($thisYearStart, $thisYearEnd) {
            $sessionDate = $slot->trainingSession?->date;
            return $sessionDate && $sessionDate->between($thisYearStart, $thisYearEnd);
        })->count();

        // Calculate week streak (consecutive weeks with at least one session taught)
        $weekStreak = $this->calculateCoachWeekStreak($taughtSlots);

        return [
            'teaching_count_this_week' => $thisWeekCount,
            'teaching_count_this_month' => $thisMonthCount,
            'teaching_count_this_year' => $thisYearCount,
            'week_streak' => $weekStreak,
            'total_sessions_taught' => $taughtSlots->count(),
        ];
    }

    /**
     * Calculate consecutive weeks with teaching sessions
     */
    private function calculateCoachWeekStreak($taughtSlots)
    {
        if ($taughtSlots->isEmpty()) {
            return 0;
        }

        // Group taught sessions by week
        $weeklySessions = $taughtSlots
            ->map(function ($slot) {
                $sessionDate = $slot->trainingSession?->date;
                if (!$sessionDate) return null;
                return $sessionDate->copy()->startOfWeek()->toDateString();
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($weeklySessions->isEmpty()) {
            return 0;
        }

        // Check if current week or last week has teaching
        $now = now();
        $currentWeekStart = $now->copy()->startOfWeek()->toDateString();
        $lastWeekStart = $now->copy()->subWeek()->startOfWeek()->toDateString();
        
        $hasCurrentWeek = $weeklySessions->contains($currentWeekStart);
        $hasLastWeek = $weeklySessions->contains($lastWeekStart);
        
        // If no teaching in current or last week, streak is 0
        if (!$hasCurrentWeek && !$hasLastWeek) {
            return 0;
        }

        // Start counting from current week or last week
        $streak = 0;
        $checkWeek = $hasCurrentWeek ? $currentWeekStart : $lastWeekStart;
        
        // Count backwards consecutive weeks
        while ($weeklySessions->contains($checkWeek)) {
            $streak++;
            $checkWeek = \Carbon\Carbon::parse($checkWeek)->subWeek()->startOfWeek()->toDateString();
        }

        return $streak;
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
