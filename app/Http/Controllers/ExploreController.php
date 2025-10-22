<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video; // Assuming you have a Video model

class ExploreController extends Controller
{
    /**
     * Shows the 'Explore' page with trending topics and featured videos.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Fetch trending videos (e.g., videos with the most likes/views in the last 24 hours).
        // For simplicity, we order by the count of associated likes.
        $trendingVideos = Video::with(['user', 'likes'])
                                ->withCount('likes')
                                ->orderByDesc('likes_count')
                                ->take(12) // Show a top list of 12 trending videos
                                ->get();

        // 2. Define a list of popular categories/hashtags to display on the Explore page.
        $trendingTopics = [
            '#foryoupage',
            '#comedycentral',
            '#learnontiktok',
            '#gaming',
            '#dancechallenge',
            '#travel',
            '#codinglife',
            '#techreview',
        ];

        // Pass the data to the 'explore.index' Blade view
        return view('explore.index', [
            'trendingVideos' => $trendingVideos,
            'trendingTopics' => $trendingTopics,
        ]);
    }
}
