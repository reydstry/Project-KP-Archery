<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()
            ->published()
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json($news);
    }

    public function show(News $news)
    {
        if ($news->publish_date->isFuture()) {
            return response()->json([
                'message' => 'News not found',
            ], 404);
        }

        return response()->json([
            'data' => $news,
        ]);
    }
}
