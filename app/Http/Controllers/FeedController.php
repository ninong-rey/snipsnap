<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\Follow;

class FeedController extends Controller
{
    /**
     * Shows the 'For You' feed (algorithmic and viral content).
     */
    public function forYou(Request $request)
    {
        $videos = Video::with(['user', 'likes'])
                        ->latest()
                        ->take(20)
                        ->get();

        return view('feed.foryou', compact('videos'));
    }

    /**
     * Shows the 'Following' feed (content from users the authenticated user follows).
     */
    public function following(Request $request)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            // FIXED: Use your actual login route name
            return redirect()->route('login.view'); 
        }

        $userId = Auth::id();

        // Use Follow model directly for reliability
        $followingIds = Follow::where('follower_id', $userId)
                            ->pluck('following_id');

        // Handle case where user isn't following anyone
        if ($followingIds->isEmpty()) {
            $videos = collect();
        } else {
            $videos = Video::whereIn('user_id', $followingIds)
                            ->with(['user', 'likes'])
                            ->latest()
                            ->take(20)
                            ->get();
        }

        return view('feed.following', compact('videos'));
    }
}