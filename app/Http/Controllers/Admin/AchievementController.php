<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Services\Admin\ContentManagementService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function __construct(
        private readonly ContentManagementService $contentManagementService,
    ) {
    }

    public function index()
    {
        return response()->json($this->contentManagementService->listAchievements());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:member,club',
            'member_id' => 'nullable|exists:members,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        if ($validated['type'] === 'member' && empty($validated['member_id'])) {
            return response()->json([
                'message' => 'The member_id field is required when type is member.',
                'errors' => [
                    'member_id' => ['The member_id field is required when type is member.'],
                ],
            ], 422);
        }

        $achievement = $this->contentManagementService->createAchievement($validated, $request->file('photo'));

        return response()->json([
            'message' => 'Achievement created successfully',
            'data' => $achievement,
        ], 201);
    }

    public function show(Achievement $achievement)
    {
        return response()->json([
            'data' => $achievement,
        ]);
    }

    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'type' => 'sometimes|required|in:member,club',
            'member_id' => 'nullable|exists:members,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $type = $validated['type'] ?? $achievement->type;
        $memberId = array_key_exists('member_id', $validated) ? $validated['member_id'] : $achievement->member_id;

        if ($type === 'member' && empty($memberId)) {
            return response()->json([
                'message' => 'The member_id field is required when type is member.',
                'errors' => [
                    'member_id' => ['The member_id field is required when type is member.'],
                ],
            ], 422);
        }

        $achievement = $this->contentManagementService->updateAchievement($achievement, $validated, $request->file('photo'));

        return response()->json([
            'message' => 'Achievement updated successfully',
            'data' => $achievement,
        ]);
    }

    public function destroy(Achievement $achievement)
    {
        $this->contentManagementService->deleteAchievement($achievement);

        return response()->json([
            'message' => 'Achievement deleted successfully',
        ]);
    }
}
