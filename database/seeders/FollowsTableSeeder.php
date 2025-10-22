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

        // Create test users
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

        foreach ($users as $userData) {
            $user = User::create($userData);
            echo "Created user: " . $user->name . " (ID: " . $user->id . ")\n";
        }

        // Follow them
        $otherUsers = User::where('id', '!=', 1)->get();
        foreach ($otherUsers as $user) {
            $me->following()->attach($user->id);
            echo "Followed user: " . $user->name . " (ID: " . $user->id . ")\n";
        }

        // Create videos - using only the columns that exist in your table
        foreach ($me->following()->get() as $user) {
            for ($i = 1; $i <= 3; $i++) {
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
        }

        echo "Setup complete! Following " . $me->following()->count() . " users with " . Video::count() . " videos total.\n";
    }
}
