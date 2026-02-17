<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

                'slots' => 'required|array|min:1',
                'slots.*.session_time_id' => 'required|exists:session_times,id',
                'slots.*.max_participants' => 'required|integer|min:1|max:50',
                'slots.*.coach_ids' => 'required|array|min:1',
                'slots.*.coach_ids.*' => 'required|exists:coaches,id',
            ],
            [
                'date.required' => 'Tanggal sesi wajib diisi.',
                'date.after_or_equal' => 'Tanggal sesi tidak boleh sebelum hari ini.',
                'date.unique' => 'Sesi untuk tanggal ini sudah ada. Pilih tanggal lain.',
                'slots.required' => 'Minimal pilih satu slot sesi.',
                'slots.min' => 'Minimal pilih satu slot sesi.',
                'slots.*.session_time_id.required' => 'Waktu sesi wajib dipilih.',
                'slots.*.max_participants.required' => 'Kuota peserta wajib diisi.',
                'slots.*.max_participants.min' => 'Kuota minimal 1 peserta.',
                'slots.*.max_participants.max' => 'Kuota maksimal 50 peserta.',
                'slots.*.coach_ids.required' => 'Setiap slot wajib memiliki minimal 1 coach.',
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
}
