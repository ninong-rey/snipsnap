<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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

        // Test users
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

        // Create or update users
        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
            echo "Created or updated user: " . $user->name . " (ID: " . $user->id . ")\n";
        }

        // Follow them if not already following
        $otherUsers = User::where('id', '!=', $me->id)->get();
        foreach ($otherUsers as $user) {
            if (!$me->following->contains($user->id)) {
                $me->following()->attach($user->id);
                echo "Followed user: " . $user->name . " (ID: " . $user->id . ")\n";
            }
        }

        // Check if videos table has 'thumbnail_url' column
        $hasThumbnail = Schema::hasColumn('videos', 'thumbnail_url');

        // Create videos for each followed user if they don't already have them
        foreach ($me->following as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $videoExists = Video::where('user_id', $user->id)
                    ->where('caption', 'Awesome video #' . $i . ' from ' . $user->name)
                    ->exists();

                if (!$videoExists) {
                    $videoData = [
                        'user_id' => $user->id,
                        'caption' => 'Awesome video #' . $i . ' from ' . $user->name,
                        'url' => 'users/' . $user->id . '/video' . $i . '.mp4',
                        'views' => rand(100, 1000),
                        'likes_count' => rand(10, 100),
                        'comments_count' => rand(5, 50),
                        'shares_count' => rand(1, 20),
                    ];

                    if ($hasThumbnail) {
                        $videoData['thumbnail_url'] = 'thumbnails/' . $user->id . '/video' . $i . '.jpg';
                    }

                    Video::create($videoData);
                    echo "Created video for: " . $user->name . "\n";
                }
            }
        }

        echo "Setup complete! Following " . $me->following()->count() . " users with " . Video::count() . " videos total.\n";
    }
}
