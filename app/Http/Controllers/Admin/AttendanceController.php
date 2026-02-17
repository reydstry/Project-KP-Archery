<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use App\Services\Admin\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {
    }

    public function activeMembers(Request $request)
    {
        $search = (string) $request->query('search', '');
        $limit = max(1, min(200, (int) $request->query('limit', 100)));

        return response()->json([
            'data' => $this->attendanceService->activeMembers($search, $limit),
        ]);
    }

    public function index(TrainingSession $trainingSession)
    {
        return response()->json([
            'session' => [
                'id' => $trainingSession->id,
                'date' => $trainingSession->date,
                'status' => $trainingSession->status?->value,
            ],
            'attendances' => $this->attendanceService->listBySession($trainingSession),
        ]);
    }

    public function store(Request $request, TrainingSession $trainingSession)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'integer', 'in:' . $trainingSession->id],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['required', 'integer', 'distinct', 'exists:members,id'],
        ]);

        $result = $this->attendanceService->bulkStore(
            trainingSession: $trainingSession,
            memberIds: $validated['member_ids'],
        );

        return response()->json([
            'message' => 'Attendance berhasil disimpan.',
            'data' => $result,
        ], 201);
    }
}
