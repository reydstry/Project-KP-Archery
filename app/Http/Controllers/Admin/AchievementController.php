<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::query()
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($achievements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:member,club',
            'member_id' => 'nullable|exists:members,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'photo_path' => 'nullable|string|max:255',
        ]);

        if ($validated['type'] === 'member' && empty($validated['member_id'])) {
            return response()->json([
                'message' => 'The member_id field is required when type is member.',
                'errors' => [
                    'member_id' => ['The member_id field is required when type is member.'],
                ],
            ], 422);
        }

        if ($validated['type'] === 'club') {
            $validated['member_id'] = null;
        }

        $achievement = Achievement::create($validated);

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
            'photo_path' => 'nullable|string|max:255',
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

        if ($type === 'club') {
            $validated['member_id'] = null;
        }

        $achievement->update($validated);

        return response()->json([
            'message' => 'Achievement updated successfully',
            'data' => $achievement->fresh(),
        ]);
    }

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();

        return response()->json([
            'message' => 'Achievement deleted successfully',
        ]);
    }
}
