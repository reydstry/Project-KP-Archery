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
        $sessionTimeIds = collect($payload['slots'])->pluck('session_time_id');

        if ($sessionTimeIds->count() !== $sessionTimeIds->unique()->count()) {
            return [
                'error' => true,
                'status' => 422,
                'body' => ['message' => 'Ada slot waktu yang dipilih lebih dari sekali.'],
            ];
        }

        try {
            DB::beginTransaction();

            $trainingSession = TrainingSession::query()->create([
                'date' => $payload['date'],
                'status' => TrainingSessionStatus::OPEN->value,
                'created_by' => $createdBy,
            ]);

            foreach ($payload['slots'] as $slotPayload) {
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
}
