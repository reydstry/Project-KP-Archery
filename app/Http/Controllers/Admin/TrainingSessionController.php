<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Services\Admin\TrainingManagementService;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function __construct(
        private readonly TrainingManagementService $trainingManagementService,
    ) {
    }

    /**
     * List training sessions (admin can see all coaches).
     */
    public function index(Request $request)
    {
        return response()->json($this->trainingManagementService->list([
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]));
    }

    /**
     * Create a new training session (admin can create for any coach).
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'date' => 'required|date|after_or_equal:today|unique:training_sessions,date',

                'slots' => 'nullable|array|min:1',
                'slots.*.session_time_id' => 'required_with:slots|exists:session_times,id',
                'slots.*.max_participants' => 'required_with:slots|integer|min:1|max:50',
                'slots.*.coach_ids' => 'required_with:slots|array|min:1',
                'slots.*.coach_ids.*' => 'required_with:slots|exists:coaches,id',
            ],
            [
                'date.required' => 'Tanggal sesi wajib diisi.',
                'date.after_or_equal' => 'Tanggal sesi tidak boleh sebelum hari ini.',
                'date.unique' => 'Sesi untuk tanggal ini sudah ada. Pilih tanggal lain.',
                'slots.min' => 'Minimal pilih satu slot sesi.',
                'slots.*.session_time_id.required_with' => 'Waktu sesi wajib dipilih.',
                'slots.*.max_participants.required_with' => 'Kuota peserta wajib diisi.',
                'slots.*.max_participants.min' => 'Kuota minimal 1 peserta.',
                'slots.*.max_participants.max' => 'Kuota maksimal 50 peserta.',
                'slots.*.coach_ids.required_with' => 'Setiap slot wajib memiliki minimal 1 coach.',
                'slots.*.coach_ids.min' => 'Setiap slot wajib memiliki minimal 1 coach.',
            ]
        );

        $result = $this->trainingManagementService->create($validated, auth()->id());

        return response()->json($result['body'], $result['status']);
    }

    /**
     * Show training session including slots.
     */
    public function show(TrainingSession $trainingSession)
    {
        return response()->json($this->trainingManagementService->detail($trainingSession));
    }

    /**
     * Update training session (date and/or status).
     */
    public function update(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate(
            [
                'date' => 'sometimes|date|after_or_equal:today|unique:training_sessions,date,' . $trainingSession->id,
                'status' => 'sometimes|string|in:open,closed,canceled',
            ],
            [
                'date.after_or_equal' => 'Tanggal sesi tidak boleh sebelum hari ini.',
                'date.unique' => 'Sesi untuk tanggal ini sudah ada. Pilih tanggal lain.',
                'status.in' => 'Status tidak valid. Pilih: open, closed, atau canceled.',
            ]
        );

        $result = $this->trainingManagementService->update($trainingSession, $validated);

        return response()->json($result['body'], $result['status']);
    }

    /**
     * Update coaches assigned to a training session.
     */
    /**
     * Update coaches assigned to a specific slot.
     */
    public function updateSlotCoaches(Request $request, TrainingSessionSlot $trainingSessionSlot)
    {
        $validated = $request->validate([
            'coach_ids' => 'required|array|min:1',
            'coach_ids.*' => 'required|exists:coaches,id',
            'max_participants' => 'nullable|integer|min:1|max:50',
        ]);

        return response()->json($this->trainingManagementService->updateSlotCoaches($trainingSessionSlot, $validated));
    }

    public function destroy(TrainingSession $trainingSession)
    {
        $result = $this->trainingManagementService->delete($trainingSession);

        return response()->json($result['body'], $result['status']);
    }

    /**
     * Update training session status.
     */
    public function updateStatus(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate(
            ['status' => 'required|string|in:open,closed,canceled'],
            [
                'status.required' => 'Status wajib diisi.',
                'status.in' => 'Status tidak valid. Pilih: open, closed, atau canceled.',
            ]
        );

        $result = $this->trainingManagementService->updateStatus($trainingSession, $validated['status']);

        return response()->json($result['body'], $result['status']);
    }

    public function sessionTimes()
    {
        return response()->json([
            'data' => SessionTime::query()
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get(['id', 'name', 'start_time', 'end_time']),
        ]);
    }

    public function coaches()
    {
        return response()->json([
            'data' => Coach::query()
                ->orderBy('name')
                ->get(['id', 'user_id', 'name']),
        ]);
    }

    /**
     * Create a new slot for an existing training session.
     */
    public function createSlot(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate(
            [
                'session_time_id' => 'required|exists:session_times,id',
                'max_participants' => 'required|integer|min:1|max:50',
                'coach_ids' => 'required|array|min:1',
                'coach_ids.*' => 'required|exists:coaches,id',
            ],
            [
                'session_time_id.required' => 'Waktu sesi wajib dipilih.',
                'session_time_id.exists' => 'Waktu sesi tidak valid.',
                'max_participants.required' => 'Kuota peserta wajib diisi.',
                'max_participants.min' => 'Kuota minimal 1 peserta.',
                'max_participants.max' => 'Kuota maksimal 50 peserta.',
                'coach_ids.required' => 'Slot wajib memiliki minimal 1 coach.',
                'coach_ids.min' => 'Slot wajib memiliki minimal 1 coach.',
                'coach_ids.*.exists' => 'ID coach tidak valid.',
            ]
        );

        $result = $this->trainingManagementService->createSlot($trainingSession, $validated);

        return response()->json($result['body'], $result['status']);
    }

    /**
     * Update a training session slot (general update for quota and coaches).
     */
    public function updateSlot(Request $request, TrainingSessionSlot $trainingSessionSlot)
    {
        $validated = $request->validate([
            'coach_ids' => 'nullable|array|min:1',
            'coach_ids.*' => 'required|exists:coaches,id',
            'max_participants' => 'nullable|integer|min:1|max:50',
        ]);

        return response()->json($this->trainingManagementService->updateSlotCoaches($trainingSessionSlot, $validated));
    }

    /**
     * Delete a training session slot.
     */
    public function deleteSlot(TrainingSessionSlot $trainingSessionSlot)
    {
        $result = $this->trainingManagementService->deleteSlot($trainingSessionSlot);

        return response()->json($result['body'], $result['status']);
    }
}
