<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function notify($fromUserId, $toUserId, $type, $videoId = null, $commentId = null)
    {
        // Don't notify yourself
        if ($fromUserId == $toUserId) {
            return null;
        }

        $message = self::generateMessage($type);

        return Notification::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'type' => $type,
            'message' => $message,
            'video_id' => $videoId,
            'comment_id' => $commentId,
            'read' => false
        ]);
    }

    private static function generateMessage($type)
    {
        $messages = [
            'like' => 'liked your video',
            'comment' => 'commented on your video', 
            'follow' => 'started following you',
            'share' => 'shared your video',
            'mention' => 'mentioned you in a comment'
        ];

        return $messages[$type] ?? 'sent you a notification';
    }

    // Helper methods for specific notification types
    public static function notifyLike($fromUserId, $toUserId, $videoId)
    {
        return self::notify($fromUserId, $toUserId, 'like', $videoId);
    }

    public static function notifyComment($fromUserId, $toUserId, $videoId, $commentId)
    {
        return self::notify($fromUserId, $toUserId, 'comment', $videoId, $commentId);
    }

    public static function notifyFollow($fromUserId, $toUserId)
    {
        return self::notify($fromUserId, $toUserId, 'follow');
    }

    public static function notifyShare($fromUserId, $toUserId, $videoId)
    {
        return self::notify($fromUserId, $toUserId, 'share', $videoId);
    }
}