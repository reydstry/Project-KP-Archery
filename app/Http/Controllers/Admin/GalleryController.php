<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\Admin\ContentManagementService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __construct(
        private readonly ContentManagementService $contentManagementService,
    ) {
    }

    public function index()
    {
        return response()->json($this->contentManagementService->listGalleries());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:training,competition,group_selfie',
            'is_active' => 'nullable|boolean',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $gallery = $this->contentManagementService->createGallery($validated, $request->file('photo'));

        return response()->json([
            'message' => 'Gallery created successfully',
            'data' => $gallery,
        ], 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json([
            'data' => $gallery,
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|in:training,competition,group_selfie',
            'is_active' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $gallery = $this->contentManagementService->updateGallery($gallery, $validated, $request->file('photo'));

        return response()->json([
            'message' => 'Gallery updated successfully',
            'data' => $gallery,
        ]);
    }

    public function destroy(Gallery $gallery)
    {
        $this->contentManagementService->deleteGallery($gallery);

        return response()->json([
            'message' => 'Gallery deleted successfully',
        ]);
    }
}
