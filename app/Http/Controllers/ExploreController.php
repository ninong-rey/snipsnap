<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class ExploreController extends Controller
{
    /**
     * Shows the 'Explore' page with trending topics and featured videos.
     */
    public function index(Request $request)
    {
        // Fetch trending videos safely
        $trendingVideos = Video::with(['user', 'likes'])
                                ->withCount('likes')
                                ->orderBy('likes_count', 'desc')
                                ->take(12)
                                ->get();

        // Popular categories/hashtags
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

        return view('explore.index', [
            'trendingVideos' => $trendingVideos,
            'trendingTopics' => $trendingTopics,
        ]);
    }
}
