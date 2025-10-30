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

// Add to the top of your VideoController
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        return view('upload');
    }

    /**
     * Handle video upload (AJAX)
     */
    public function store(Request $request)
{
    // ADD ERROR DISPLAY AT TOP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    \Log::info('=== UPLOAD STARTED ===');
    
    try {
        $user = auth()->user();
        \Log::info('User authenticated', ['user_id' => $user->id]);

        // FIX: Reduce max size to match server limits (2MB)
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:2048', // 2MB max
        ]);
        \Log::info('Validation passed');

        // Store file on Render
        $path = $request->file('video')->store('videos', 'public');
        \Log::info('File stored - PATH RETURNED:', ['path' => $path]);
        
        // FIX: Provide default caption if empty
        $caption = $request->input('caption', 'My awesome video');
        
        $videoData = [
            'user_id' => $user->id,
            'url' => $path,
            'file_path' => $path,
            'caption' => $caption, // This was NULL causing the error!
            'views' => 0,
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
        ];
        
        $video = Video::create($videoData);
        \Log::info('Video created successfully', ['video_id' => $video->id, 'saved_path' => $video->file_path]);

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully!',
            'video_id' => $video->id,
            'file_path' => $video->file_path,
            'redirect_url' => url('/web'),
        ]);

    } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
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
}
