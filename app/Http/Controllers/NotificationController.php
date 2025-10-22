<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'all');
        
        // Base query
        $query = Notification::where('to_user_id', $user->id)
            ->with(['fromUser', 'video'])
            ->latest();
        
        // Filter by tab
        if ($tab !== 'all') {
            $query->where('type', $tab);
        }
        
        $notifications = $query->paginate(20);
        
        // Counts for each tab
        $unreadCount = Notification::where('to_user_id', $user->id)
            ->where('read', false)
            ->count();
            
        $likeCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'like')
            ->where('read', false)
            ->count();
            
        $commentCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'comment')
            ->where('read', false)
            ->count();
            
        $followCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'follow')
            ->where('read', false)
            ->count();
        
        return view('notifications', compact(
            'notifications',
            'unreadCount',
            'likeCount',
            'commentCount',
            'followCount',
            'tab'
        ));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('to_user_id', Auth::id())
            ->firstOrFail();
            
        $notification->update(['read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Notification::where('to_user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
            
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount()
    {
        $count = Notification::where('to_user_id', Auth::id())
            ->where('read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}