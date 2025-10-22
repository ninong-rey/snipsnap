<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // Add this import

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'video_id' => 'required|exists:videos,id',
                'content' => 'required|string|max:1000',
                'parent_id' => 'nullable', // Remove exists validation for now
            ]);

            $commentData = [
                'video_id' => $request->video_id,
                'user_id' => Auth::id(),
                'content' => $request->content,
            ];

            // Only add parent_id if the column exists
            if (Schema::hasColumn('comments', 'parent_id')) {
                $commentData['parent_id'] = $request->parent_id;
            }

            $comment = Comment::create($commentData);

            // Eager load the user relationship to ensure it's available
            $comment->load(['user' => function($query) {
                $query->select('id', 'name', 'username');
            }]);

            return response()->json([
                'success' => true,
                'message' => $request->parent_id ? 'Reply posted successfully' : 'Comment posted successfully',
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            // Use the correct Log facade
            Log::error('Comment store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to post: ' . $e->getMessage()
            ], 500);
        }
    }
}