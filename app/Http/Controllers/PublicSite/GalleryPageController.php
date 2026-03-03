<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\HighlightsService;

class GalleryPageController extends Controller
{
    public function __construct(
        private readonly HighlightsService $highlightsService,
    ) {}

    /**
     * GET /galeri — main gallery page
     */
    public function index()
    {
        return view('pages.galeri', [
            'highlightItems' => $this->highlightsService->getHighlights(limit: 12, excerptLength: 150),
        ]);
    }

    /**
     * GET /galeri/highlights — archive page with filter
     */
    public function highlights()
    {
        $activeType = request('type', 'all');

        $items = $this->highlightsService->getHighlights(limit: 100, excerptLength: 180);

        if (in_array($activeType, ['news', 'achievement'], true)) {
            $items = $items->where('type', $activeType)->values();
        }

        return view('pages.galeri-highlights', [
            'items'      => $items,
            'activeType' => $activeType,
        ]);
    }
}
