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
        return view('upload');
    }

    /**
     * Handle video upload (AJAX)
     */
    public function store(Request $request)
    {
        // ADDED: Detailed logging
        \Log::info('=== UPLOAD STARTED ===');
        \Log::info('Request data: ', $request->all());
        
        try {
            // Ensure user is logged in
            $user = auth()->user();
            \Log::info('User: ' . ($user ? $user->id : 'Not authenticated'));
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'You must be logged in to upload.'], 401);
            }

            // Validate file
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,webm|max:20480',
            ]);
            \Log::info('Validation passed');

            // Store file
            $path = $request->file('video')->store('videos', 'public');
            \Log::info('File stored: ' . $path);

            // Save to DB
            $video = new Video();
            $video->user_id = $user->id;
            $video->url = $path;
            $video->file_path = $path;
            $video->caption = $request->input('caption') ?? '';
            $video->save();
            \Log::info('Video saved to DB, ID: ' . $video->id);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully!',
                'redirect_url' => route('my-web'),
            ]);

        } catch (\Exception $e) {
            // THIS WILL SHOW THE EXACT ERROR
            \Log::error('UPLOAD ERROR: ' . $e->getMessage());
            \Log::error('Error location: ' . $e->getFile() . ':' . $e->getLine());
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