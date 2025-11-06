<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Video;
use App\Models\Follow;

class ProfileController extends Controller
{
    /**
     * Show a user's profile, or the authenticated user if none specified
     */
    public function show(?User $user = null)
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            abort(404, 'User not found');
        }

        // Counts safely
        $likesTotal = optional($user->videos()->withCount('likes')->get())->sum('likes_count') ?? 0;
        $followingCount = optional($user->following())->count() ?? 0;
        $followersCount = optional($user->followers())->count() ?? 0;

        // Format counts
        $likesTotal = $this->formatCount($likesTotal);
        $followingCount = $this->formatCount($followingCount);
        $followersCount = $this->formatCount($followersCount);

        // Videos safely
        $videos = $user->videos()->latest()->get()->filter(function ($video) {
            return Storage::disk('public')->exists($video->path ?? '');
        });

        // Avatar fallback
        $avatar = $user->avatar && Storage::disk('public')->exists($user->avatar)
            ? $user->avatar
            : 'avatars/default-avatar.png';

        return view('profile', compact(
            'user',
            'videos',
            'likesTotal',
            'followingCount',
            'followersCount',
            'avatar'
        ));
    }

    /**
     * Update the authenticated user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $data = $request->validate([
            'username' => ['nullable','string','alpha_dash','min:3','max:24',Rule::unique('users')->ignore($user->id)],
            'name'     => 'nullable|string|max:30',
            'bio'      => 'nullable|string|max:80',
            'phone'    => 'nullable|string|max:20',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle avatar safely
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'user' => [
                'username' => $user->username,
                'name' => $user->name,
                'bio' => $user->bio,
                'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/default-avatar.png'),
            ]
        ]);
    }

    /**
     * Follow a user
     */
    public function follow(User $userToFollow)
    {
        $user = Auth::user();
        if (!$user || $user->id === $userToFollow->id) {
            return response()->json(['success' => false, 'message' => 'Invalid action.'], 422);
        }

        $isFollowing = Follow::where('follower_id', $user->id)
                              ->where('following_id', $userToFollow->id)
                              ->exists();

        if (!$isFollowing) {
            Follow::create([
                'follower_id'  => $user->id,
                'following_id' => $userToFollow->id,
            ]);
        }

        $followersCount = optional($userToFollow->followers())->count() ?? 0;
        $isNowFollowing = !$isFollowing;

        return response()->json([
            'success' => true,
            'message' => $isNowFollowing ? 'User followed!' : 'Already following',
            'following' => $isNowFollowing,
            'followers_count' => $followersCount
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $userToUnfollow)
    {
        $user = Auth::user();
        if (!$user) return back()->with('error', 'Not authenticated');

        Follow::where('follower_id', $user->id)
              ->where('following_id', $userToUnfollow->id)
              ->delete();

        return back()->with('success', 'User unfollowed.');
    }

    /**
     * Format numbers into readable format
     */
    private function formatCount($n)
    {
        $n = (float) $n;

        if ($n >= 1000000) return round($n / 1000000, 1) . 'M';
        if ($n >= 1000) return round($n / 1000, 1) . 'K';
        return (string) $n;
    }
}
