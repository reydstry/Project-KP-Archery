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
            'photo_path' => 'nullable|string|max:255',
        ]);

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
            'photo_path' => 'nullable|string|max:255',
        ]);

        $news->update($validated);

        return response()->json([
            'message' => 'News updated successfully',
            'data' => $news->fresh(),
        ]);
    }

    public function destroy(News $news)
    {
        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully',
        ]);
    }
}
