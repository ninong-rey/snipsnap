<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoLike;
use App\Models\Comment;
use Cloudinary\Cloudinary;

class VideoController extends Controller
{
   public function __construct()
{
    $this->middleware('auth')->except(['show']);
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
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    \Log::info('=== UPLOAD STARTED ===');
    
    try {
        $user = auth()->user();
        \Log::info('User authenticated', ['user_id' => $user->id]);

        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,webm|max:51200',
        ]);
        \Log::info('Validation passed');

        // UPLOAD TO CLOUDINARY DIRECTLY
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
        
        $upload = $cloudinary->uploadApi()->upload(
            $request->file('video')->getRealPath(),
            [
                'folder' => 'videos',
                'resource_type' => 'video'
            ]
        );

        $videoUrl = $upload['secure_url'];
        $publicId = $upload['public_id'];
        
        \Log::info('File uploaded to Cloudinary:', ['url' => $videoUrl]);

        // Get caption or use default
        $caption = $request->input('caption', 'Check out my video!');
        
        // Store video data
        $videoData = [
            'user_id' => $user->id,
            'url' => $videoUrl, // Cloudinary URL
            'file_path' => $publicId,
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
            'message' => 'Video uploaded to Cloudinary successfully!',
            'video_id' => $video->id,
            'video_url' => $videoUrl,
            'caption' => $video->caption,
            'redirect_url' => url('/web'),
        ]);

    } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Show a single video page - IMPROVED VERSION
     */
    public function show($id)
{
    try {
        Log::info("=== VIDEO SHOW STARTED ===");
        Log::info("Loading video ID: {$id}");

        // Eager load user with safe fallback
        $video = Video::with(['user'])->find($id);

        if (!$video) {
            Log::warning("Video not found: {$id}");
            abort(404, 'Video not found');
        }

        Log::info("Video found", [
            'video_id' => $video->id,
            'user_id' => $video->user_id,
            'url' => $video->url,
            'caption' => $video->caption
        ]);

        // Check if user exists
        if (!$video->user) {
            Log::warning("Video user not found for video: {$video->id}");
        }

        // Check if video file exists
        $videoPath = 'public/' . $video->url;
        if (!Storage::exists($videoPath)) {
            Log::warning("Video file not found in storage: {$videoPath}");
        } else {
            Log::info("Video file exists in storage: {$videoPath}");
        }

        // Safely get liked videos count - FIXED AUTH CALLS
        $likedVideosCount = 0;
        if (app('auth')->check()) {
            try {
                $user = app('auth')->user();
                // Use the relationship safely
                $likedVideosCount = $user->videoLikes()->count();
            } catch (\Exception $e) {
                Log::warning("Error getting liked videos count: " . $e->getMessage());
                $likedVideosCount = 0;
            }
        }

        // Increment views
        $video->increment('views');
        Log::info("Views incremented for video: {$video->id}");

        Log::info("Rendering video show view");

        return view('video.show', compact('video', 'likedVideosCount'));

    } catch (\Exception $e) {
        Log::error("VideoController Error for ID {$id}: " . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        return response()->view('errors.500', [
            'message' => 'Unable to load video: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Like a video
     */
    public function like($id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error("Like error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Like failed'
            ], 500);
        }
    }

    /**
     * Unlike a video
     */
    public function unlike($id)
    {
        try {
            $video = Video::findOrFail($id);

            VideoLike::where('user_id', Auth::id())
                ->where('video_id', $video->id)
                ->delete();

            $video->decrement('likes_count');

            return response()->json([
                'success' => true,
                'likes_count' => $video->fresh()->likes_count,
            ]);
        } catch (\Exception $e) {
            Log::error("Unlike error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unlike failed'
            ], 500);
        }
    }

    /**
     * Share a video
     */
    public function share($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->increment('shares_count');

            return response()->json([
                'success' => true,
                'shares_count' => $video->fresh()->shares_count,
            ]);
        } catch (\Exception $e) {
            Log::error("Share error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Share failed'
            ], 500);
        }
    }
}