<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoLike;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the upload page
     */
    public function create()
{
    $video = new Video([
        'url' => null,
        'caption' => null,
    ]);

    // Set user relation safely
    $video->setRelation('user', Auth::user() ?? new User());

    $video->setRelation('comments', collect());
    $video->likes_count = 0;
    $video->comments_count = 0;
    $video->shares_count = 0;

    return view('upload', compact('video'));
}


    /**
     * Handle video upload (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:5242880', // 5GB in KB
            'caption' => 'nullable|string|max:500',
        ]);

        try {
            $videoFile = $request->file('video');
            $userId = Auth::id();
            $basePath = 'users/' . $userId;

            // Store uploaded video
            $videoFileName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();
            $videoPath = Storage::disk('public')->putFileAs($basePath, $videoFile, $videoFileName);

            // Save to database
            $video = Video::create([
                'user_id' => $userId,
                'caption' => $request->input('caption'),
                'url' => $videoPath,
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully!',
                'video_id' => $video->id,
                'video_url' => Storage::url($videoPath),
                'redirect_url' => route('my-web'),
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
     * Show a single video page
     */
    public function show($id)
    {
        $video = Video::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->findOrFail($id);

        $video->increment('views');

        return view('video', compact('video'));
    }

    /**
     * Like a video
     */
    public function like($id)
    {
        $video = Video::findOrFail($id);

        $existingLike = VideoLike::where('user_id', Auth::id())
            ->where('video_id', $video->id)
            ->first();

        if (!$existingLike) {
            VideoLike::create([
                'user_id' => Auth::id(),
                'video_id' => $video->id,
            ]);
            $video->increment('likes_count');
        }

        return response()->json([
            'success' => true,
            'likes_count' => $video->fresh()->likes_count,
        ]);
    }

    /**
     * Unlike a video
     */
    public function unlike($id)
    {
        $video = Video::findOrFail($id);

        VideoLike::where('user_id', Auth::id())
            ->where('video_id', $video->id)
            ->delete();

        $video->decrement('likes_count');

        return response()->json([
            'success' => true,
            'likes_count' => $video->fresh()->likes_count,
        ]);
    }

    /**
     * Share a video
     */
    public function share($id)
    {
        $video = Video::findOrFail($id);
        $video->increment('shares_count');

        return response()->json([
            'success' => true,
            'shares_count' => $video->fresh()->shares_count,
        ]);
    }

    /**
     * Debug relationships (optional)
     */
    public function testRelationships()
    {
        $video = Video::first();

        if (!$video) {
            $video = Video::create([
                'user_id' => Auth::id(),
                'caption' => 'Test Video',
                'url' => 'test/video.mp4',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
            ]);
        }

        echo "<h1>Video Relationships Test</h1><pre>";
        echo "Video ID: {$video->id}\n";
        echo "Caption: {$video->caption}\n";
        echo "User: " . ($video->user->name ?? 'Guest') . "\n";
        echo "Comments Count: " . $video->comments()->count() . "\n";
        echo "Likes Count: " . $video->likes_count . "\n";
        echo "Shares Count: " . $video->shares_count . "\n";
        echo "</pre>";
        exit;
    }
}
