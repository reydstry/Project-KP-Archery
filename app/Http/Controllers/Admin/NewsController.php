<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('news', 'public');
            $validated['photo_path'] = $path;
        }

        unset($validated['photo']);
        $news = News::create($validated);

        return response()->json([
            'message' => 'News created successfully',
            'data' => $news,
        ], 201);
    }

    public function show(News $news)
    {
        return response()->json([
            'data' => $news,
        ]);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'publish_date' => 'sometimes|required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($news->photo_path && \Storage::disk('public')->exists($news->photo_path)) {
                \Storage::disk('public')->delete($news->photo_path);
            }
            $path = $request->file('photo')->store('news', 'public');
            $validated['photo_path'] = $path;
        }

        unset($validated['photo']);
        $news->update($validated);

        return response()->json([
            'message' => 'News updated successfully',
            'data' => $news->fresh(),
        ]);
    }

    public function destroy(News $news)
    {
        // Delete photo if exists
        if ($news->photo_path && \Storage::disk('public')->exists($news->photo_path)) {
            \Storage::disk('public')->delete($news->photo_path);
        }

        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully',
        ]);
    }
}
