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
    /**
 * Handle video upload (AJAX)
 */
public function store(Request $request)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    \Log::info('=== UPLOAD STARTED ===');
    
    try {
        $user = auth()->user();
        \Log::info('User authenticated', ['user_id' => $user->id]);

        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:2048',
        ]);
        \Log::info('Validation passed');

        // Store file on Render - THIS RETURNS JUST THE PATH
        $path = $request->file('video')->store('videos', 'public');
        \Log::info('File stored:', ['path' => $path]);
        
        // FIX: Store just the path, not the full URL
        $caption = $request->input('caption'); 
        
        \Log::info('Caption received:', ['caption' => $caption]);
        
        if (empty($caption) || trim($caption) === '') {
            $caption = 'Check out my video!';
            \Log::info('Using default caption');
        }
        
        // FIXED: Store just the path, NOT the full URL
        $videoData = [
            'user_id' => $user->id,
            'url' => $path, // ← FIX: Store "videos/filename.mp4" not full URL
            'file_path' => $path,
            'caption' => $caption,
            'views' => 0,
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
        ];
        
        \Log::info('Video data to save:', $videoData);
        
        $video = Video::create($videoData);
        \Log::info('Video created successfully', ['video_id' => $video->id]);

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully!',
            'video_id' => $video->id,
            'file_path' => $video->file_path,
            'video_url' => secure_asset('storage/' . $video->url), // Use asset() here for response only
            'caption' => $video->caption,
            'redirect_url' => url('/web'),
        ]);

    } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Show a single video page
     */
    /**
 * Show a single video page
 */
public function show($id)
{
    try {
        \Log::info("Loading video ID: {$id}");
        
        $video = Video::with(['user'])
            ->find($id);

        if (!$video) {
            \Log::warning("Video not found: {$id}");
            abort(404, 'Video not found');
        }

        \Log::info("Video found: {$video->id}");

        // Increment views
        $video->increment('views');

        return view('video.show', compact('video'));
        
    } catch (\Exception $e) {
        \Log::error("VideoController Error for ID {$id}: " . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->view('errors.500', [
            'message' => 'Unable to load video'
        ], 500);
    }
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
