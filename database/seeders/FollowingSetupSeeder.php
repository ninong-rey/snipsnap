<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Hash;

class FollowingSetupSeeder extends Seeder
{
    public function run()
    {
        $me = User::find(1);
        
        if (!$me) {
            echo "User with ID 1 not found!\n";
            return;
        }

        echo "Current user: " . $me->name . " (ID: " . $me->id . ")\n";

        // Create test users only if they don't exist
        $users = [
            [
                'name' => 'Emma Wilson',
                'username' => 'emmaw', 
                'email' => 'emma@test.com',
                'password' => Hash::make('password123'),
                'bio' => 'Content creator and artist'
            ],
            [
                'name' => 'Alex Chen',
                'username' => 'alexc',
                'email' => 'alex@test.com',
                'password' => Hash::make('password123'),
                'bio' => 'Travel vlogger and photographer'
            ],
            [
                'name' => 'Sarah Johnson',
                'username' => 'sarahj',
                'email' => 'sarah@test.com',
                'password' => Hash::make('password123'),
                'bio' => 'Fitness coach and motivator'
            ]
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::where('username', $userData['username'])->first();
            if (!$user) {
                $user = User::create($userData);
                echo "Created user: " . $user->name . " (ID: " . $user->id . ")\n";
            } else {
                echo "User already exists: " . $user->name . " (ID: " . $user->id . ")\n";
            }
            $createdUsers[] = $user;
        }

        // Follow them (only if not already following)
        $otherUsers = User::where('id', '!=', 1)->get();
        foreach ($otherUsers as $user) {
            if (!$me->following()->where('following_id', $user->id)->exists()) {
                $me->following()->attach($user->id);
                echo "Followed user: " . $user->name . " (ID: " . $user->id . ")\n";
            } else {
                echo "Already following: " . $user->name . " (ID: " . $user->id . ")\n";
            }
        }

        // Create videos for followed users (only if they don't have videos yet)
        foreach ($me->following()->get() as $user) {
            $existingVideos = Video::where('user_id', $user->id)->count();
            if ($existingVideos < 3) {
                for ($i = $existingVideos + 1; $i <= 3; $i++) {
                    Video::create([
                        'user_id' => $user->id,
                        'caption' => 'Awesome video #' . $i . ' from ' . $user->name,
                        'url' => 'users/' . $user->id . '/video' . $i . '.mp4',
                        'thumbnail_url' => 'thumbnails/' . $user->id . '/video' . $i . '.jpg',
                        'views' => rand(100, 1000),
                        'likes_count' => rand(10, 100),
                        'comments_count' => rand(5, 50),
                        'shares_count' => rand(1, 20)
                    ]);
                    echo "Created video for: " . $user->name . "\n";
                }
            } else {
                echo "User " . $user->name . " already has " . $existingVideos . " videos\n";
            }
        }

        echo "Setup complete! Following " . $me->following()->count() . " users with " . Video::count() . " videos total.\n";
    }
}