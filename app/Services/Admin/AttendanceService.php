<?php

namespace App\Services\Admin;

use App\Enums\StatusMember;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\TrainingSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function listBySession(TrainingSession $trainingSession)
    {
        return Attendance::query()
            ->with([
                'member:id,name,phone,status,is_active',
            ])
            ->where('session_id', $trainingSession->id)
            ->latest('id')
            ->get(['id', 'session_id', 'member_id', 'created_at']);
    }

    public function bulkStore(TrainingSession $trainingSession, array $memberIds): array
    {
        $memberIds = collect($memberIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($memberIds->isEmpty()) {
            throw ValidationException::withMessages([
                'member_ids' => ['Minimal 1 member harus dipilih.'],
            ]);
        }

        $activeMembers = Member::query()
            ->whereIn('id', $memberIds)
            ->where('is_active', true)
            ->where('status', StatusMember::STATUS_ACTIVE->value)
            ->pluck('id');

        $invalidMemberIds = $memberIds->diff($activeMembers)->values();
        if ($invalidMemberIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'member_ids' => [
                    'Hanya member ACTIVE yang dapat dicatat kehadirannya.',
                    'Member tidak valid: ' . $invalidMemberIds->implode(', '),
                ],
            ]);
        }

        $existingMemberIds = Attendance::query()
            ->where('session_id', $trainingSession->id)
            ->whereIn('member_id', $memberIds)
            ->pluck('member_id');

        if ($existingMemberIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'member_ids' => [
                    'Attendance duplikat terdeteksi untuk session yang sama.',
                    'Member sudah tercatat: ' . $existingMemberIds->implode(', '),
                ],
            ]);
        }

        $now = now();
        $rows = $memberIds->map(fn ($memberId) => [
            'session_id' => $trainingSession->id,
            'member_id' => $memberId,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        DB::table('attendances')->insert($rows);

        return [
            'session_id' => $trainingSession->id,
            'inserted_count' => count($rows),
            'member_ids' => $memberIds->all(),
        ];
    }

    public function syncForSession(TrainingSession $trainingSession, array $memberIds): array
    {
        $memberIds = collect($memberIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $activeMembers = Member::query()
            ->whereIn('id', $memberIds)
            ->where('is_active', true)
            ->where('status', StatusMember::STATUS_ACTIVE->value)
            ->pluck('id');

        $invalidMemberIds = $memberIds->diff($activeMembers)->values();
        if ($invalidMemberIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'member_ids' => [
                    'Hanya member ACTIVE yang dapat dicatat kehadirannya.',
                    'Member tidak valid: ' . $invalidMemberIds->implode(', '),
                ],
            ]);
        }

        $existingMemberIds = Attendance::query()
            ->where('session_id', $trainingSession->id)
            ->pluck('member_id')
            ->map(fn ($id) => (int) $id);

        $toDelete = $existingMemberIds->diff($memberIds)->values();
        $toInsert = $memberIds->diff($existingMemberIds)->values();

        DB::transaction(function () use ($trainingSession, $toDelete, $toInsert) {
            if ($toDelete->isNotEmpty()) {
                Attendance::query()
                    ->where('session_id', $trainingSession->id)
                    ->whereIn('member_id', $toDelete)
                    ->delete();
            }

            if ($toInsert->isNotEmpty()) {
                $now = now();
                $rows = $toInsert->map(fn ($memberId) => [
                    'session_id' => $trainingSession->id,
                    'member_id' => $memberId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                DB::table('attendances')->insert($rows);
            }
        });

        return [
            'session_id' => $trainingSession->id,
            'present_count' => $memberIds->count(),
            'inserted_count' => $toInsert->count(),
            'deleted_count' => $toDelete->count(),
            'member_ids' => $memberIds->all(),
        ];
    }

    public function activeMembers(string $search = '', int $limit = 100)
    {
        $query = Member::query()
            ->where('is_active', true)
            ->where('status', StatusMember::STATUS_ACTIVE->value)
            ->orderBy('name');

        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        return $query->limit($limit)->get(['id', 'name', 'phone', 'status', 'is_active']);
    }
}
