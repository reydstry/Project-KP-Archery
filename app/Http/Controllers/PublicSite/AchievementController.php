<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|in:member,club',
        ]);

        $achievements = Achievement::query()
            ->published()
            ->when(isset($validated['type']), function ($query) use ($validated) {
                $query->type($validated['type']);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($achievements);
    }

    public function show(Achievement $achievement)
    {
        if ($achievement->date->isFuture()) {
            return response()->json([
                'message' => 'Achievement not found',
            ], 404);
        }

        return response()->json([
            'data' => $achievement,
        ]);
    }
}
