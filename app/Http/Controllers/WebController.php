<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Video;
use App\Models\User;

class WebController extends Controller
{
    /**
     * Show all videos on the homepage
     */
    public function index()
{
    $videos = Video::with(['user', 'comments.user'])
        ->withCount(['likes', 'comments', 'shares'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($video) {
            if (empty($video->url)) {
                $video->video_url = 'https://assets.mixkit.co/videos/preview/mixkit-tree-with-yellow-flowers-1173-large.mp4';
            } else {
                $video->video_url = $video->url;
            }
            return $video;
        });

    return view('web', compact('videos'));
}

    /**
     * Handle AJAX video upload from upload.blade.php
     */
    public function store(Request $request)
    {
        // ✅ Validate uploaded file and caption
        $request->validate([
            'video'   => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
            'caption' => 'nullable|string|max:255',
        ]);

        try {
            // ✅ Save uploaded file to storage/app/public/videos
            $path = $request->file('video')->store('videos', 'public');

            // ✅ Save to the database
            $video = Video::create([
                'user_id' => Auth::id(),
                'url' => $path,
                'caption' => $request->caption,
                'views' => 0,
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
            ]);

            // ✅ Return JSON so the JS fetch() can handle it
            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully!',
                'redirect_url' => route('web'),
            ]);

        } catch (\Exception $e) {
            Log::error('Video upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show profile page with user videos
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Get user's videos with counts
        $videos = Video::where('user_id', $user->id)
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->get();

        // Calculate total likes across all user videos
        $likes_total = Video::where('user_id', $user->id)->sum('likes_count');
        
        // Get follow counts using DB query to avoid relationship issues
        $followers_count = DB::table('follows')->where('following_id', $user->id)->count();
        $following_count = DB::table('follows')->where('follower_id', $user->id)->count();

        return view('profile', compact('user', 'videos', 'likes_total', 'followers_count', 'following_count'));
    }

    /**
     * Update user profile - FIXED VERSION
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name'     => 'required|string|max:255',
                'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
                'bio'      => 'nullable|string|max:255',
                'avatar'   => 'nullable|image|max:2048',
            ]);

            // Use DB query to update instead of Eloquent save()
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'bio' => $request->bio,
                'updated_at' => now(),
            ];

            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = $avatarPath;
            }

            // Update using DB query to avoid save() method issues
            DB::table('users')
                ->where('id', $user->id)
                ->update($updateData);

            return redirect()->back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Explore users page
     */
    public function exploreUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->withCount(['videos'])
            ->paginate(12);

        // Get top 10 trending creators by video count
        $trendingUsers = User::where('id', '!=', Auth::id())
            ->withCount(['videos'])
            ->orderBy('videos_count', 'desc')
            ->take(10)
            ->get();

        return view('explore-user', compact('users', 'trendingUsers'));
    }

    /**
     * Show videos from users you're following - FIXED VERSION
     */
    public function following()
    {
        try {
            $user = Auth::user();
            
            // Get the IDs of users being followed using DB query
            $followingUserIds = DB::table('follows')
                ->where('follower_id', $user->id)
                ->pluck('following_id');
            
            // If no users are being followed, return empty collection
            if ($followingUserIds->isEmpty()) {
                $videos = collect();
                return view('web', compact('videos'))->with('info', 'You are not following anyone yet. Follow users to see their videos here.');
            }

            // Get videos from followed users
            $videos = Video::with(['user', 'comments.user'])
                ->whereIn('user_id', $followingUserIds)
                ->withCount(['likes', 'comments', 'shares'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('web', compact('videos'));

        } catch (\Exception $e) {
            Log::error('Following videos error: ' . $e->getMessage());
            
            // Fallback: return all videos if there's an error
            $videos = Video::with(['user', 'comments.user'])
                ->withCount(['likes', 'comments', 'shares'])
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
                
            return view('web', compact('videos'))->with('error', 'Unable to load following feed. Showing popular videos instead.');
        }
    }

    /**
     * Show a single video
     */
    public function showVideo($id)
    {
        $video = Video::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->findOrFail($id);

        // Increment view count
        $video->increment('views');

        return view('video', compact('video'));
    }

    /**
     * Get trending videos
     */
    public function trending()
    {
        $videos = Video::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->orderBy('likes_count', 'desc')
            ->orderBy('views', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('web', compact('videos'));
    }

    /**
     * Show user profile by username
     */
    public function showUserProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        // Check if viewing own profile
        $isOwnProfile = Auth::check() && Auth::id() === $user->id;
        
        // Get user's videos with counts
        $videos = Video::where('user_id', $user->id)
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->get();

        // Calculate total likes across all user videos
        $likes_total = Video::where('user_id', $user->id)->sum('likes_count');
        
        // Get follow counts using DB queries
        $followers_count = DB::table('follows')->where('following_id', $user->id)->count();
        $following_count = DB::table('follows')->where('follower_id', $user->id)->count();

        return view('profile', compact('user', 'videos', 'likes_total', 'followers_count', 'following_count', 'isOwnProfile'));
    }

    /**
     * Follow a user
     */
    public function followUser($userId)
    {
        try {
            $userToFollow = User::findOrFail($userId);
            $currentUser = Auth::user();

            // Check if not already following using DB query
            $isFollowing = DB::table('follows')
                ->where('follower_id', $currentUser->id)
                ->where('following_id', $userId)
                ->exists();

            if (!$isFollowing) {
                DB::table('follows')->insert([
                    'follower_id' => $currentUser->id,
                    'following_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $followers_count = DB::table('follows')->where('following_id', $userId)->count();
                
                return response()->json([
                    'success' => true,
                    'message' => 'You are now following ' . $userToFollow->name,
                    'followers_count' => $followers_count
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'You are already following this user'
            ]);

        } catch (\Exception $e) {
            Log::error('Follow user error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to follow user'
            ], 500);
        }
    }

    /**
     * Unfollow a user
     */
    public function unfollowUser($userId)
    {
        try {
            $userToUnfollow = User::findOrFail($userId);
            $currentUser = Auth::user();

            DB::table('follows')
                ->where('follower_id', $currentUser->id)
                ->where('following_id', $userId)
                ->delete();
            
            $followers_count = DB::table('follows')->where('following_id', $userId)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'You have unfollowed ' . $userToUnfollow->name,
                'followers_count' => $followers_count
            ]);

        } catch (\Exception $e) {
            Log::error('Unfollow user error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unfollow user'
            ], 500);
        }
    }

    /**
     * Check if user is following another user
     */
    public function checkFollowStatus($userId)
    {
        try {
            $currentUser = Auth::user();
            
            $isFollowing = DB::table('follows')
                ->where('follower_id', $currentUser->id)
                ->where('following_id', $userId)
                ->exists();

            return response()->json([
                'success' => true,
                'is_following' => $isFollowing
            ]);

        } catch (\Exception $e) {
            Log::error('Check follow status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'is_following' => false
            ], 500);
        }
    }
    /**
 * Show friends page
 */
/**
 * Show friends page
 */
public function friends()
{
    $user = Auth::user();
    
    // Get user's friends (people who follow each other - mutual follows)
    $friends = User::whereHas('followers', function($query) use ($user) {
        $query->where('follower_id', $user->id);
    })->whereHas('following', function($query) use ($user) {
        $query->where('following_id', $user->id);
    })
    ->withCount(['videos', 'followers'])
    ->get();

    // Get active friends (remove last_activity check for now)
    $activeFriends = User::whereHas('followers', function($query) use ($user) {
        $query->where('follower_id', $user->id);
    })->whereHas('following', function($query) use ($user) {
        $query->where('following_id', $user->id);
    })
    ->withCount(['videos'])
    ->limit(10)
    ->get();

    // Get friend suggestions (people you don't follow yet)
    $suggestions = User::where('id', '!=', $user->id)
        ->whereNotIn('id', function($query) use ($user) {
            $query->select('following_id')
                  ->from('follows')
                  ->where('follower_id', $user->id);
        })
        ->withCount(['videos', 'followers'])
        ->inRandomOrder()
        ->limit(20)
        ->get();

    return view('friends', compact('friends', 'activeFriends', 'suggestions'));
}
/**
 * Show notifications page
 */
public function notifications()
{
    $user = Auth::user();
    
    // You would typically get these from your database
    $notifications = [
        // This would be real notification data from your database
    ];

    // Counts for the tab badges
    $likeCount = 12;    // Would come from: Notification::where('type', 'like')->where('read', false)->count()
    $commentCount = 8;  // Would come from: Notification::where('type', 'comment')->where('read', false)->count()
    $messageCount = 5;  // Would come from: Notification::where('type', 'message')->where('read', false)->count()
    $followCount = 3;   // Would come from: Notification::where('type', 'follow')->where('read', false)->count()

    return view('notifications', compact('notifications', 'likeCount', 'commentCount', 'messageCount', 'followCount'));
}
}