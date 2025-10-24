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
    \Log::info('=== UPLOAD STARTED ===');
    \Log::info('File received', [
        'has_file' => $request->hasFile('video'),
        'file_valid' => $request->file('video') ? $request->file('video')->isValid() : false,
        'file_size' => $request->file('video') ? $request->file('video')->getSize() : 0,
        'file_name' => $request->file('video') ? $request->file('video')->getClientOriginalName() : 'none',
    ]);

    try {
        // Validate the upload
        $validated = $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv|max:51200',
            'caption' => 'nullable|string|max:500'
        ]);
        
        \Log::info('Validation passed');

        // Check storage directory
        $storagePath = storage_path('app/public/videos');
        if (!is_dir($storagePath)) {
            \Log::info('Creating storage directory');
            mkdir($storagePath, 0755, true);
        }

        \Log::info('Attempting to store file');
        
        // Store the video - use 'url' column instead of 'video_path'
        $videoPath = $request->file('video')->store('videos', 'public');
        \Log::info('File stored successfully', ['path' => $videoPath]);

        \Log::info('Creating video record');
        
        // Create video record - FIXED: use 'url' instead of 'video_path'
        $video = Video::create([
            'user_id' => Auth::id(),
            'caption' => $request->caption ?: 'Untitled Video', // Use 'caption' for title
            'url' => $videoPath, // Use 'url' column instead of 'video_path'
        ]);

        \Log::info('Video created successfully', ['video_id' => $video->id]);

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully!',
            'redirect_url' => route('my-web')
        ]);

    } catch (\Exception $e) {
        \Log::error('UPLOAD FAILED', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
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