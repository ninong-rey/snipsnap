<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        $authId = Auth::id();

        $conversationUsers = User::whereHas('sentMessages', fn($q) => $q->where('receiver_id', $authId))
            ->orWhereHas('receivedMessages', fn($q) => $q->where('sender_id', $authId))
            ->where('id', '!=', $authId)
            ->distinct()
            ->get();

        return view('messages.index', [
            'conversationUsers' => $conversationUsers,
            'currentConversationUser' => null,
            'messages' => collect(),
        ]);
    }

    public function show($userId)
    {
        $authId = Auth::id();
        $user = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('id', 'asc')
            ->get();

        $conversationUsers = User::whereHas('sentMessages', fn($q) => $q->where('receiver_id', $authId))
            ->orWhereHas('receivedMessages', fn($q) => $q->where('sender_id', $authId))
            ->where('id', '!=', $authId)
            ->distinct()
            ->get();

        return view('messages.index', [
            'conversationUsers' => $conversationUsers,
            'currentConversationUser' => $user,
            'messages' => $messages,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
        ]);

        return response()->json([
            'id' => $message->id,
            'content' => $message->content,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    public function fetchNew($receiverId, $lastId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $authId)->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $authId);
            })
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages->map(fn($msg) => [
            'id' => $msg->id,
            'content' => $msg->content,
            'sender_id' => $msg->sender_id,
            'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
        ]));
    }
    // Add this to your existing MessagesController
public function sendCallInvitation(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'room_name' => 'required|string',
        'call_type' => 'required|in:voice,video',
        'invite_link' => 'required|url'
    ]);

    // Create a call invitation message
    $message = Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'content' => "📞 I started a {$request->call_type} call. Join me: {$request->invite_link}",
        'is_call_invitation' => true,
        'call_room_name' => $request->room_name,
        'call_type' => $request->call_type
    ]);

    return response()->json([
        'success' => true,
        'message' => $message
    ]);
}
}
