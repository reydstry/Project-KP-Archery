<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementDetailController extends Controller
{
    /**
     * Display achievement detail page
     */
    public function show(Achievement $achievement)
    {
        // Check if achievement is published
        if ($achievement->date > now()) {
            abort(404, 'Achievement not found or not yet published');
        }

        // Load member relation if exists
        $achievement->load('member');

        return view('pages.achievement-detail', compact('achievement'));
    }
}
