<?php

namespace App\Services\Admin;

use App\Enums\UserRoles;
use App\Models\Coach;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CoachManagementService
{
    public function list(): array
    {
        $coaches = User::query()
            ->where('role', UserRoles::COACH)
            ->latest()
            ->get();

        return [
            'message' => 'Data coaches berhasil diambil',
            'data' => $coaches,
        ];
    }

    public function create(array $payload): User
    {
        return User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'phone' => $payload['phone'] ?? null,
            'role' => UserRoles::COACH,
        ]);
    }

    public function detail(User $coach): ?array
    {
        if ($coach->role !== UserRoles::COACH) {
            return null;
        }

        return [
            'message' => 'Data coach berhasil diambil',
            'data' => $coach,
            'statistics' => $this->calculateCoachStats($coach),
        ];
    }

    public function update(User $coach, array $payload): ?array
    {
        if ($coach->role !== UserRoles::COACH) {
            return null;
        }

        $updateData = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'] ?? null,
        ];

        if (!empty($payload['password'])) {
            $updateData['password'] = Hash::make($payload['password']);
        }

        $coach->update($updateData);

        return [
            'message' => 'Coach berhasil diupdate',
            'data' => $coach->fresh(),
        ];
    }

    public function delete(User $coach): ?array
    {
        if ($coach->role !== UserRoles::COACH) {
            return null;
        }

        $coach->delete();

        return [
            'message' => 'Coach berhasil dihapus',
        ];
    }

    private function calculateCoachStats(User $coach): array
    {
        $now = now();
        $coachProfile = Coach::query()->where('user_id', $coach->id)->first();

        if (!$coachProfile) {
            return [
                'teaching_count_this_week' => 0,
                'teaching_count_this_month' => 0,
                'teaching_count_this_year' => 0,
                'week_streak' => 0,
                'total_sessions_taught' => 0,
            ];
        }

        $taughtSlots = TrainingSessionSlot::query()
            ->whereHas('coaches', function ($query) use ($coachProfile) {
                $query->where('coach_id', $coachProfile->id);
            })
            ->with(['trainingSession'])
            ->get()
            ->filter(function (TrainingSessionSlot $slot) {
                $sessionDate = $slot->trainingSession?->date;

                return $sessionDate && $sessionDate->lte(now());
            });

        $thisWeekCount = $this->countInRange($taughtSlots, $now->copy()->startOfWeek(), $now->copy()->endOfWeek());
        $thisMonthCount = $this->countInRange($taughtSlots, $now->copy()->startOfMonth(), $now->copy()->endOfMonth());
        $thisYearCount = $this->countInRange($taughtSlots, $now->copy()->startOfYear(), $now->copy()->endOfYear());

        return [
            'teaching_count_this_week' => $thisWeekCount,
            'teaching_count_this_month' => $thisMonthCount,
            'teaching_count_this_year' => $thisYearCount,
            'week_streak' => $this->calculateCoachWeekStreak($taughtSlots),
            'total_sessions_taught' => $taughtSlots->count(),
        ];
    }

    private function countInRange(Collection $slots, Carbon $from, Carbon $to): int
    {
        return $slots->filter(function (TrainingSessionSlot $slot) use ($from, $to) {
            $sessionDate = $slot->trainingSession?->date;

            return $sessionDate && $sessionDate->between($from, $to);
        })->count();
    }

    private function calculateCoachWeekStreak(Collection $taughtSlots): int
    {
        if ($taughtSlots->isEmpty()) {
            return 0;
        }

        $weeklySessions = $taughtSlots
            ->map(function (TrainingSessionSlot $slot) {
                $sessionDate = $slot->trainingSession?->date;

                if (!$sessionDate) {
                    return null;
                }

                return $sessionDate->copy()->startOfWeek()->toDateString();
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($weeklySessions->isEmpty()) {
            return 0;
        }

        $now = now();
        $currentWeekStart = $now->copy()->startOfWeek()->toDateString();
        $lastWeekStart = $now->copy()->subWeek()->startOfWeek()->toDateString();
        $hasCurrentWeek = $weeklySessions->contains($currentWeekStart);
        $hasLastWeek = $weeklySessions->contains($lastWeekStart);

        if (!$hasCurrentWeek && !$hasLastWeek) {
            return 0;
        }

        $streak = 0;
        $checkWeek = $hasCurrentWeek ? $currentWeekStart : $lastWeekStart;

        while ($weeklySessions->contains($checkWeek)) {
            $streak++;
            $checkWeek = Carbon::parse($checkWeek)->subWeek()->startOfWeek()->toDateString();
        }

        return $streak;
    }
}
