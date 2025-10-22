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

        /** @var \App\Models\User $user */

        // ✅ Use relationship queries instead of property access to prevent null issues
        $likesTotal = $user->videos()->withCount('likes')->get()->sum('likes_count');
        $followingCount = $user->following()->count();
        $followersCount = $user->followers()->count();

        // ✅ Format counts (e.g., 12500 -> 12.5K)
        $likesTotal = $this->formatCount($likesTotal);
        $followingCount = $this->formatCount($followingCount);
        $followersCount = $this->formatCount($followersCount);

        // ✅ Retrieve user videos safely
        $videos = $user->videos()->latest()->get();

        return view('profile', compact(
            'user',
            'videos',
            'likesTotal',
            'followingCount',
            'followersCount'
        ));
    }

    /**
     * Show the edit profile page
     */
    public function edit()
    {
        return view('profile_edit', ['user' => Auth::user()]);
    }

    /**
     * Update the authenticated user's profile
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'username' => [
                'nullable',
                'string',
                'alpha_dash',
                'min:3',
                'max:24',
                Rule::unique('users')->ignore($user->id),
            ],
            'name'   => 'nullable|string|max:30',
            'bio'    => 'nullable|string|max:80',
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Follow a user
     */
    public function follow(User $userToFollow)
    {
        $user = Auth::user();

        if ($user->id === $userToFollow->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if (!Follow::where('follower_id', $user->id)
            ->where('following_id', $userToFollow->id)
            ->exists()) {
            Follow::create([
                'follower_id'  => $user->id,
                'following_id' => $userToFollow->id,
            ]);
        }

        return back()->with('success', 'User followed!');
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $userToUnfollow)
    {
        $user = Auth::user();

        Follow::where('follower_id', $user->id)
            ->where('following_id', $userToUnfollow->id)
            ->delete();

        return back()->with('success', 'User unfollowed.');
    }

    /**
     * Format numbers into readable format (e.g., 12500 -> 12.5K)
     */
    private function formatCount($n)
    {
        $n = (float) $n;

        if ($n >= 1000000) {
            return round($n / 1000000, 1) . 'M';
        }
        if ($n >= 1000) {
            return round($n / 1000, 1) . 'K';
        }

        return (string) $n;
    }
    
}
