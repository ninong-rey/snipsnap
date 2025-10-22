<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{
    /**
     * Toggle Follow / Unfollow
     */
    public function toggleFollow($id)
    {
        $userToFollow = User::findOrFail($id);
        $auth = Auth::user();

        if (!$auth) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        if ($auth->id === $userToFollow->id) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself'], 400);
        }

        try {
            if ($auth->isFollowing($userToFollow)) {
                $auth->following()->detach($userToFollow->id);
                $following = false;
            } else {
                $auth->following()->attach($userToFollow->id);
                $following = true;
            }

            return response()->json([
                'success' => true,
                'following' => $following,
                'followers_count' => $userToFollow->followers()->count(),
                'following_count' => $auth->following()->count(),
                'message' => $following ? 'Followed successfully' : 'Unfollowed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Follow toggle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Follow action failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get follow status of a user
     */
    public function followStatus(User $user)
    {
        $auth = Auth::user();
        if (!$auth) {
            return response()->json(['following' => false]);
        }

        $isFollowing = $auth->isFollowing($user);

        return response()->json(['following' => $isFollowing]);
    }

    /**
     * Get videos from followed users
     */
    public function followingVideos()
    {
        $auth = Auth::user();
        if (!$auth) return redirect()->route('login');

        $followingIds = $auth->following()->pluck('following_id');

        $followingVideos = Video::with(['user', 'comments.user'])
            ->whereIn('user_id', $followingIds)
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->get();

        return view('following', compact('followingVideos'));
    }

    /**
     * Debug follow system
     */
    public function debugFollowSystem()
    {
        $auth = Auth::user();
        if (!$auth) return response()->json(['error' => 'Not logged in']);

        return response()->json([
            'user_id' => $auth->id,
            'following_count' => $auth->following()->count(),
            'followers_count' => $auth->followers()->count(),
            'following_ids' => $auth->following()->pluck('following_id'),
        ]);
    }
}