<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;
use App\Models\User;

class VideosTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                Video::create([
                    'user_id' => $user->id,
                    'path' => "videos/sample{$i}.mp4",
                    'caption' => "Sample video $i by {$user->username}",
                    'views' => rand(0, 1000),
                ]);
            }
        }
    }
}
