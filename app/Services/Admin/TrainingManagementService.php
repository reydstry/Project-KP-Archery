<?php

namespace App\Services\Admin;

use App\Enums\TrainingSessionStatus;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TrainingManagementService
{
    public function paginateSessions(int $perPage = 25): LengthAwarePaginator
    {
        return TrainingSession::query()
            ->select(['id', 'date', 'status', 'created_by', 'created_at', 'updated_at'])
            ->with([
                'slots:id,training_session_id,session_time_id,max_participants,created_at,updated_at',
                'slots.sessionTime:id,name,start_time,end_time',
                'slots.coaches:id,name',
            ])
            ->latest('date')
            ->paginate($perPage);
    }

    public function list(array $filters)
    {
        $query = TrainingSession::query()
            ->select(['id', 'date', 'status', 'created_by', 'created_at', 'updated_at'])
            ->withCount('attendances')
            ->with([
                'slots:id,training_session_id,session_time_id,max_participants,created_at,updated_at',
                'slots.sessionTime:id,name,start_time,end_time',
                'slots.coaches:id,name',
                'attendances:id,session_id,member_id,created_at',
                'attendances.member:id,name,phone',
                'createdBy:id,name',
            ]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', $filters['end_date']);
        }

        return $query->orderBy('date', 'desc')->paginate(15);
    }

    public function create(array $payload, ?int $createdBy): array
    {
        // Handle optional slots
        $slots = $payload['slots'] ?? [];
        
        if (!empty($slots)) {
            $sessionTimeIds = collect($slots)->pluck('session_time_id');

            if ($sessionTimeIds->count() !== $sessionTimeIds->unique()->count()) {
                return [
                    'error' => true,
                    'status' => 422,
                    'body' => ['message' => 'Ada slot waktu yang dipilih lebih dari sekali.'],
                ];
            }
        }

        try {
            DB::beginTransaction();

            // Use status from payload if provided, otherwise default to OPEN
            $status = $payload['status'] ?? TrainingSessionStatus::OPEN->value;
            
            $trainingSession = TrainingSession::query()->create([
                'date' => $payload['date'],
                'status' => $status,
                'created_by' => $createdBy,
            ]);

            // Only create slots if provided
            if (!empty($slots)) {
                foreach ($slots as $slotPayload) {
                    $slot = TrainingSessionSlot::query()->create([
                        'training_session_id' => $trainingSession->id,
                        'session_time_id' => $slotPayload['session_time_id'],
                        'max_participants' => $slotPayload['max_participants'],
                    ]);

                    $coachIds = collect($slotPayload['coach_ids'])
                        ->map(fn ($id) => (int) $id)
                        ->filter()
                        ->unique()
                        ->all();

                    $slot->coaches()->attach($coachIds);
                }
            }

            DB::commit();

            return [
                'error' => false,
                'status' => 201,
                'body' => [
                    'message' => 'Training session created successfully',
                    'data' => $trainingSession->fresh()
                        ->load([
                            'slots:id,training_session_id,session_time_id,max_participants,created_at,updated_at',
                            'slots.sessionTime:id,name,start_time,end_time',
                            'slots.coaches:id,name',
                            'createdBy:id,name',
                        ]),
                ],
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Failed to create training session',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function detail(TrainingSession $trainingSession)
    {
        $trainingSession->applyAutoClose(now());

        return $trainingSession->load([
            'slots:id,training_session_id,session_time_id,max_participants,created_at,updated_at',
            'slots.sessionTime:id,name,start_time,end_time',
            'slots.coaches:id,name',
            'attendances.member:id,name,phone',
            'createdBy:id,name',
        ]);
    }

    public function updateSlotCoaches(TrainingSessionSlot $trainingSessionSlot, array $payload): array
    {
        $coachIds = collect($payload['coach_ids'])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $trainingSessionSlot->coaches()->sync($coachIds);

        if (array_key_exists('max_participants', $payload) && $payload['max_participants'] !== null) {
            $trainingSessionSlot->update([
                'max_participants' => $payload['max_participants'],
            ]);
        }

        return [
            'message' => 'Coaches updated successfully',
            'data' => $trainingSessionSlot->fresh()
                ->load(['sessionTime:id,name,start_time,end_time', 'coaches:id,name']),
        ];
    }

    public function delete(TrainingSession $trainingSession): array
    {
        $hasAttendances = $trainingSession->attendances()->exists();

        if ($hasAttendances) {
            return [
                'error' => true,
                'status' => 422,
                'body' => ['message' => 'Cannot delete session that already has attendance records'],
            ];
        }

        $slotIds = $trainingSession->slots()->pluck('id');

        if ($slotIds->isNotEmpty()) {
            DB::table('training_session_slot_coach')
                ->whereIn('training_session_slot_id', $slotIds)
                ->delete();

            TrainingSessionSlot::query()
                ->whereIn('id', $slotIds)
                ->delete();
        }

        $trainingSession->delete();

        return [
            'error' => false,
            'status' => 200,
            'body' => ['message' => 'Training session deleted successfully'],
        ];
    }

    public function update(TrainingSession $trainingSession, array $data): array
    {
        try {
            $updateData = [];
            
            if (isset($data['date'])) {
                $updateData['date'] = $data['date'];
            }
            
            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
            }
            
            if (empty($updateData)) {
                return [
                    'error' => true,
                    'status' => 422,
                    'body' => ['message' => 'Tidak ada data yang diupdate'],
                ];
            }

            $trainingSession->update($updateData);

            return [
                'error' => false,
                'status' => 200,
                'body' => [
                    'message' => 'Session berhasil diperbarui',
                    'data' => $trainingSession->fresh(),
                ],
            ];
        } catch (\Throwable $exception) {
            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Gagal memperbarui session',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function updateStatus(TrainingSession $trainingSession, string $status): array
    {
        try {
            $trainingSession->update(['status' => $status]);

            return [
                'error' => false,
                'status' => 200,
                'body' => [
                    'message' => 'Status berhasil diubah',
                    'data' => $trainingSession->fresh(),
                ],
            ];
        } catch (\Throwable $exception) {
            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Gagal mengubah status',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function createSlot(TrainingSession $trainingSession, array $payload): array
    {
        // Check if a slot with the same session_time_id already exists
        $existingSlot = $trainingSession->slots()
            ->where('session_time_id', $payload['session_time_id'])
            ->exists();

        if ($existingSlot) {
            return [
                'error' => true,
                'status' => 422,
                'body' => ['message' => 'Slot untuk waktu ini sudah ada pada sesi ini.'],
            ];
        }

        try {
            DB::beginTransaction();

            $slot = TrainingSessionSlot::query()->create([
                'training_session_id' => $trainingSession->id,
                'session_time_id' => $payload['session_time_id'],
                'max_participants' => $payload['max_participants'],
            ]);

            $coachIds = collect($payload['coach_ids'])
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->all();

            $slot->coaches()->attach($coachIds);

            DB::commit();

            return [
                'error' => false,
                'status' => 201,
                'body' => [
                    'message' => 'Slot berhasil ditambahkan',
                    'data' => $slot->fresh()
                        ->load(['sessionTime:id,name,start_time,end_time', 'coaches:id,name']),
                ],
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Gagal menambahkan slot',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function deleteSlot(TrainingSessionSlot $slot): array
    {
        try {
            // Remove coach assignments first
            $slot->coaches()->detach();
            
            // Delete the slot
            $slot->delete();

            return [
                'error' => false,
                'status' => 200,
                'body' => ['message' => 'Slot berhasil dihapus'],
            ];
        } catch (\Throwable $exception) {
            return [
                'error' => true,
                'status' => 500,
                'body' => [
                    'message' => 'Gagal menghapus slot',
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }
}
