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
    // ✅ Validate uploaded file and caption
    $request->validate([
        'video'   => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
        'caption' => 'nullable|string|max:255',
    ]);

    try {
        // ✅ Save uploaded file directly to /public/videos/
        $file = $request->file('video');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('videos'), $filename);

        // ✅ Save to the database
        $video = Video::create([
            'user_id' => Auth::id(),
            'url' => $filename, // just filename, no 'videos/' prefix
            'caption' => $request->caption,
            'views' => 0,
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
        ]);

        // ✅ Return JSON for frontend JS
        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully!',
            'redirect_url' => route('my-web'),
        ]);

    } catch (\Exception $e) {
        \Log::error('Video upload error: ' . $e->getMessage());
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
}