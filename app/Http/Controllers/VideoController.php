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
    // Ensure user is logged in
    $user = auth()->user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'You must be logged in to upload.'], 401);
    }

    // Validate file
    $request->validate([
        'video' => 'required|file|mimes:mp4,mov,avi,webm|max:20480',
    ]);

    // Store file
    $path = $request->file('video')->store('videos', 'public');

    // Save to DB - ⭐⭐ ADD file_path ⭐⭐
    $video = new Video();
    $video->user_id = $user->id;
    $video->url = asset('storage/' . $path);  // Full URL
    $video->file_path = $path;  // ⭐⭐ ADD THIS LINE ⭐⭐
    $video->caption = $request->input('caption') ?? '';
    $video->save();

    return response()->json([
        'success' => true,
        'message' => 'Video uploaded successfully!',
        'redirect_url' => route('my-web'),
    ]);
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