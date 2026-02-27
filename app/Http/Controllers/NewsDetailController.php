<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsDetailController extends Controller
{
    /**
     * Display news detail page
     */
    public function show(News $news)
    {
        // Check if news is published
        if ($news->publish_date > now()) {
            abort(404, 'News not found or not yet published');
        }

        return view('pages.news-detail', compact('news'));
    }
}
