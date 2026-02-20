<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\Admin\ContentManagementService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(
        private readonly ContentManagementService $contentManagementService,
    ) {
    }

    public function index()
    {
        return response()->json($this->contentManagementService->listNews());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $news = $this->contentManagementService->createNews($validated, $request->file('photo'));

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

        $news = $this->contentManagementService->updateNews($news, $validated, $request->file('photo'));

        return response()->json([
            'message' => 'News updated successfully',
            'data' => $news,
        ]);
    }

    public function destroy(News $news)
    {
        $this->contentManagementService->deleteNews($news);

        return response()->json([
            'message' => 'News deleted successfully',
        ]);
    }
}
