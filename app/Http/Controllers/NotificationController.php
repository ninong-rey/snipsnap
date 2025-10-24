<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Counts for tabs & unread
        $unreadCount = Notification::where('to_user_id', $user->id)
            ->where('read', false)
            ->count();
            
        $likeCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'like')
            ->count();
            
        $commentCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'comment')
            ->count();
            
        $followCount = Notification::where('to_user_id', $user->id)
            ->where('type', 'follow')
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
    
    // Updated to return all counts for AJAX
    // In NotificationController.php
public function getUnreadCounts()
{
    $user = Auth::user();

    return response()->json([
        'unread'   => Notification::where('to_user_id', $user->id)->where('read', false)->count(),
        'likes'    => Notification::where('to_user_id', $user->id)->where('type', 'like')->count(),
        'comments' => Notification::where('to_user_id', $user->id)->where('type', 'comment')->count(),
        'follows'  => Notification::where('to_user_id', $user->id)->where('type', 'follow')->count(),
    ]);
}
public function fetchLatest(Request $request)
{
    $user = Auth::user();
    $tab = $request->get('tab', 'all');

    $query = Notification::where('to_user_id', $user->id)
        ->with(['fromUser', 'video'])
        ->latest();

    if ($tab !== 'all') {
        $query->where('type', $tab);
    }

    $notifications = $query->take(20)->get(); // latest 20

    return response()->json([
        'notifications' => $notifications,
    ]);
}


}
