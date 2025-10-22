<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Video;

class UserController extends Controller
{
    /**
     * Show user profile
     */
    public function profile($id)
{
    $user = User::withCount(['followers', 'following', 'videos'])
                ->findOrFail($id);

    $authUser = Auth::user();
    $isFollowing = $authUser ? $authUser->isFollowing($user) : false;

    // Pass user's videos and liked videos
    $videos = $user->videos()
                  ->withCount(['likes', 'comments', 'shares'])
                  ->latest()
                  ->get();

    // Fix: Load liked videos for the tab
    $likedVideos = $user->likedVideos()
                       ->withCount(['likes', 'comments', 'shares'])
                       ->latest()
                       ->get();
    $likedVideosCount = $likedVideos->count();

    return view('profile', compact(
        'user', 
        'videos', 
        'likedVideos', // Add this
        'likedVideosCount', 
        'isFollowing'
    ));
}

    /**
     * Get user's followers list
     */
    public function followers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers()
                         ->withCount(['followers', 'following', 'videos'])
                         ->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    /**
     * Get users that this user is following
     */
    public function following($id)
    {
        $user = User::findOrFail($id);
        $following = $user->following()
                         ->withCount(['followers', 'following', 'videos'])
                         ->paginate(20);

        return view('users.following', compact('user', 'following'));
    }

    /**
     * Get user's videos
     */
    public function videos($id)
    {
        $user = User::findOrFail($id);
        $videos = $user->videos()
                      ->withCount(['likes', 'comments', 'shares'])
                      ->with(['user', 'comments.user'])
                      ->latest()
                      ->paginate(12);

        return view('users.videos', compact('user', 'videos'));
    }

    /**
     * Get user's liked videos
     */
    public function likedVideos($id)
    {
        $user = User::findOrFail($id);
        $likedVideos = $user->likedVideos()
                           ->withCount(['likes', 'comments', 'shares'])
                           ->with(['user', 'comments.user'])
                           ->latest()
                           ->paginate(12);

        return view('users.liked-videos', compact('user', 'likedVideos'));
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return redirect()->back();
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('username', 'LIKE', "%{$query}%")
                    ->withCount(['followers', 'following', 'videos'])
                    ->paginate(20);

        return view('users.search', compact('users', 'query'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
        ]);

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $path;
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user suggestions (users to follow)
     */
    public function suggestions()
    {
        $authUser = Auth::user();
        
        if (!$authUser) {
            return response()->json([]);
        }

        // Get users not followed by auth user, excluding themselves
        $suggestions = User::where('id', '!=', $authUser->id)
                          ->whereNotIn('id', $authUser->following()->pluck('following_id'))
                          ->withCount(['followers', 'videos'])
                          ->inRandomOrder()
                          ->limit(10)
                          ->get();

        return response()->json($suggestions);
    }

    /**
     * Get user stats for dashboard
     */
    public function stats($id)
    {
        $user = User::withCount([
            'followers', 
            'following', 
            'videos',
            'likes',
            'sentMessages',
            'receivedMessages'
        ])->findOrFail($id);

        $totalVideoLikes = $user->videos()->withCount('likes')->get()->sum('likes_count');
        $totalVideoViews = $user->videos()->sum('views');

        return response()->json([
            'followers_count' => $user->followers_count,
            'following_count' => $user->following_count,
            'videos_count' => $user->videos_count,
            'likes_count' => $user->likes_count,
            'total_video_likes' => $totalVideoLikes,
            'total_video_views' => $totalVideoViews,
            'messages_sent' => $user->sent_messages_count,
            'messages_received' => $user->received_messages_count,
        ]);
    }
    
}