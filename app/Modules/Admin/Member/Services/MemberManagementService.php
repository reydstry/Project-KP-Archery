<?php

namespace App\Modules\Admin\Member\Services;

use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberManagementService
{
    public function list(): array
    {
        $members = Member::query()
            ->select([
                'id',
                'user_id',
                'registered_by',
                'name',
                'phone',
                'is_self',
                'status',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->with([
                'user:id,name,email,phone,role',
                'registeredBy:id,name,email,phone',
            ])
            ->latest('created_at')
            ->get();

        return [
            'message' => 'Data members berhasil diambil',
            'data' => $members,
        ];
    }

    public function create(array $payload, ?int $registeredBy): Member
    {
        return DB::transaction(function () use ($payload, $registeredBy) {
            $userId = $payload['user_id'] ?? null;

            if (!$userId) {
                $user = User::query()->create([
                    'name' => $payload['name'],
                    'role' => UserRoles::MEMBER->value,
                    'email' => 'member+' . Str::uuid() . '@focusonex.local',
                    'phone' => $payload['phone'] ?? null,
                    'password' => null,
                ]);

                $userId = $user->id;
            }

            return Member::query()->create([
                'user_id' => $userId,
                'registered_by' => $payload['registered_by'] ?? $registeredBy,
                'name' => $payload['name'],
                'phone' => $payload['phone'] ?? null,
                'is_self' => $payload['is_self'] ?? true,
                'is_active' => $payload['is_active'] ?? true,
            ]);
        });
    }

    public function detail(Member $member): array
    {
        $member->load([
            'user:id,name,email,phone,role',
            'registeredBy:id,name,email,phone',
            'memberPackages' => function ($query) {
                $query->select([
                    'id',
                    'member_id',
                    'package_id',
                    'total_sessions',
                    'used_sessions',
                    'start_date',
                    'end_date',
                    'is_active',
                    'validated_by',
                    'validated_at',
                    'created_at',
                ]);
            },
            'memberPackages.package:id,name,is_active,duration_days,session_count',
        ]);

        return [
            'message' => 'Data member berhasil diambil',
            'data' => $member,
            'statistics' => $this->calculateMemberStats($member),
        ];
    }

    public function update(Member $member, array $payload): array
    {
        $member->update($payload);

        return [
            'message' => 'Member berhasil diupdate',
            'data' => $member->fresh(['user', 'registeredBy']),
        ];
    }

    public function deactivate(Member $member): array
    {
        $member->update([
            'is_active' => false,
            'status' => StatusMember::STATUS_INACTIVE->value,
        ]);

        return [
            'message' => 'Member berhasil dinonaktifkan',
        ];
    }

    public function restore(int $memberId): array
    {
        $member = Member::query()->findOrFail($memberId);
        $member->update([
            'is_active' => true,
            'status' => StatusMember::STATUS_ACTIVE->value,
        ]);

        return [
            'message' => 'Member berhasil diaktifkan kembali',
            'data' => $member->fresh(['user', 'registeredBy']),
        ];
    }

    public function bookingCandidates(array $filters): array
    {
        $query = Member::query()->with(['memberPackages' => function ($query) {
            $query->active()->with('package');
        }]);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (array_key_exists('is_active', $filters)) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        $members = $query->orderBy('name')->get();

        $data = $members->map(function (Member $member) {
            $activePackages = $member->memberPackages->filter(function ($memberPackage) {
                $packageOk = true;

                if ($memberPackage->relationLoaded('package') && $memberPackage->package) {
                    $packageOk = (bool) $memberPackage->package->is_active;
                }

                return (bool) $memberPackage->is_active
                    && $packageOk
                    && $memberPackage->end_date
                    && $memberPackage->end_date->isFuture()
                    && $memberPackage->used_sessions < $memberPackage->total_sessions;
            })->values();

            return [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'status' => $member->status,
                'is_active' => $member->is_active,
                'active_packages' => $activePackages->map(function ($memberPackage) {
                    return [
                        'id' => $memberPackage->id,
                        'package_name' => $memberPackage->package->name ?? 'Unknown',
                        'total_sessions' => $memberPackage->total_sessions,
                        'used_sessions' => $memberPackage->used_sessions,
                        'remaining_sessions' => $memberPackage->total_sessions - $memberPackage->used_sessions,
                        'start_date' => $memberPackage->start_date?->toDateString(),
                        'end_date' => $memberPackage->end_date?->toDateString(),
                    ];
                })->values(),
            ];
        })->values();

        if (!empty($filters['has_active_package'])) {
            $data = $data->filter(fn (array $member) => count($member['active_packages']) > 0)->values();
        }

        return [
            'data' => $data,
        ];
    }

    private function calculateMemberStats(Member $member): array
    {
        $now = now();

        $attendances = Attendance::query()
            ->where('member_id', $member->id)
            ->with(['session'])
            ->get();

        $thisWeekCount = $this->countInRange($attendances, $now->copy()->startOfWeek(), $now->copy()->endOfWeek());
        $thisMonthCount = $this->countInRange($attendances, $now->copy()->startOfMonth(), $now->copy()->endOfMonth());
        $thisYearCount = $this->countInRange($attendances, $now->copy()->startOfYear(), $now->copy()->endOfYear());

        return [
            'training_count_this_week' => $thisWeekCount,
            'training_count_this_month' => $thisMonthCount,
            'training_count_this_year' => $thisYearCount,
            'week_streak' => $this->calculateWeekStreak($attendances),
            'total_trainings' => $attendances->count(),
        ];
    }

    private function countInRange(Collection $attendances, Carbon $from, Carbon $to): int
    {
        return $attendances->filter(function ($attendance) use ($from, $to) {
            $sessionDate = $attendance->session?->date;

            return $sessionDate && $sessionDate->between($from, $to);
        })->count();
    }

    private function calculateWeekStreak(Collection $attendances): int
    {
        if ($attendances->isEmpty()) {
            return 0;
        }

        $weeklyAttendances = $attendances
            ->map(function ($attendance) {
                $sessionDate = $attendance->session?->date;

                if (!$sessionDate) {
                    return null;
                }

                return $sessionDate->copy()->startOfWeek()->toDateString();
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($weeklyAttendances->isEmpty()) {
            return 0;
        }

        $now = now();
        $currentWeekStart = $now->copy()->startOfWeek()->toDateString();
        $lastWeekStart = $now->copy()->subWeek()->startOfWeek()->toDateString();

        $hasCurrentWeek = $weeklyAttendances->contains($currentWeekStart);
        $hasLastWeek = $weeklyAttendances->contains($lastWeekStart);

        if (!$hasCurrentWeek && !$hasLastWeek) {
            return 0;
        }

        $streak = 0;
        $checkWeek = $hasCurrentWeek ? $currentWeekStart : $lastWeekStart;

        while ($weeklyAttendances->contains($checkWeek)) {
            $streak++;
            $checkWeek = Carbon::parse($checkWeek)->subWeek()->startOfWeek()->toDateString();
        }

        return $streak;
    }
}
