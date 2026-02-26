<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|in:training,competition,group_selfie',
        ]);

        $galleries = Gallery::query()
            ->active()
            ->when(isset($validated['category']), function ($query) use ($validated) {
                $query->category($validated['category']);
            })
            ->ordered()
            ->paginate(20);

        return response()->json($galleries);
    }

    public function show(Gallery $gallery)
    {
        if (!$gallery->is_active) {
            return response()->json([
                'message' => 'Gallery not found',
            ], 404);
        }

        return response()->json([
            'data' => $gallery,
        ]);
    }
}
