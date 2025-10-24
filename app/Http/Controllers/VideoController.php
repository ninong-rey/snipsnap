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
        // Simple create method - just return the view
        return view('upload');
    }

    /**
     * Handle video upload (AJAX)
     */
    public function store(Request $request)
    {
        // Regenerate session to prevent timeout during upload
        $request->session()->regenerate();
        
        // Validate the upload
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv|max:51200', // 50MB max for testing
            'caption' => 'nullable|string|max:500'
        ]);

        try {
            // Store the video
            $videoPath = $request->file('video')->store('videos', 'public');
            
            // Create video record
            $video = Video::create([
                'user_id' => Auth::id(),
                'title' => $request->caption ?: 'Untitled Video',
                'video_path' => $videoPath,
                'caption' => $request->caption,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully!',
                'redirect_url' => route('my-web')
            ]);

        } catch (\Exception $e) {
            // Regenerate session on error
            $request->session()->regenerate();
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