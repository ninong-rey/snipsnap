<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\User;
use App\Models\Video;

class LikesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $videos = Video::all();

        foreach ($users as $user) {
            $likedVideos = $videos->random(5);
            foreach ($likedVideos as $video) {
                Like::create([
                    'user_id' => $user->id,
                    'video_id' => $video->id,
                ]);
            }
        }
    }
}
