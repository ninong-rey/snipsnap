@php
use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages - SnipSnap</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --accent: #ff0050;
      --text-color: #000;
      --muted: #666;
      --light-bg: #f8f8f8;
      --border: #eee;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
      color: var(--text-color);
      overflow: hidden;
      height: 100vh;
    }

    /* ==== SKELETON LOADER BASE STYLES ==== */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
      position: relative;
      overflow: hidden;
    }

    .skeleton::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      animation: shimmerSlide 2s infinite ease-in-out;
    }

    @keyframes shimmer {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }

    @keyframes shimmerSlide {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    /* ==== SIDEBAR SKELETON ==== */
    .sidebar.skeleton-loading .logo,
    .sidebar.skeleton-loading .search-box,
    .sidebar.skeleton-loading .menu a,
    .sidebar.skeleton-loading form button {
      position: relative;
      overflow: hidden;
    }

    .sidebar.skeleton-loading .logo::before,
    .sidebar.skeleton-loading .search-box::before,
    .sidebar.skeleton-loading .menu a::before,
    .sidebar.skeleton-loading form button::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 8px;
      z-index: 1;
    }

    .sidebar.skeleton-loading .logo > *,
    .sidebar.skeleton-loading .search-box > *,
    .sidebar.skeleton-loading .menu a > *,
    .sidebar.skeleton-loading form button > * {
      opacity: 0;
    }

    /* ==== MESSAGES CONTENT SKELETON ==== */
    .messages-container.skeleton-loading .messages-title,
    .messages-container.skeleton-loading .new-chat-btn,
    .messages-container.skeleton-loading .conversations-search input,
    .messages-container.skeleton-loading .conversation-item,
    .messages-container.skeleton-loading .chat-header,
    .messages-container.skeleton-loading .message,
    .messages-container.skeleton-loading .chat-input,
    .messages-container.skeleton-loading .send-btn {
      position: relative;
    }

    /* Hide actual content during skeleton loading */
    .messages-container.skeleton-loading .messages-title,
    .messages-container.skeleton-loading .new-chat-btn,
    .messages-container.skeleton-loading .conversations-search input,
    .messages-container.skeleton-loading .conversation-avatar,
    .messages-container.skeleton-loading .conversation-name,
    .messages-container.skeleton-loading .conversation-time,
    .messages-container.skeleton-loading .conversation-preview,
    .messages-container.skeleton-loading .unread-badge,
    .messages-container.skeleton-loading .chat-user-avatar,
    .messages-container.skeleton-loading .chat-user-name,
    .messages-container.skeleton-loading .chat-user-status,
    .messages-container.skeleton-loading .chat-actions button,
    .messages-container.skeleton-loading .message-text,
    .messages-container.skeleton-loading .message-time,
    .messages-container.skeleton-loading .chat-input,
    .messages-container.skeleton-loading .send-btn {
      opacity: 0;
    }

    /* Skeleton for messages header */
    .messages-container.skeleton-loading .messages-title::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 120px;
      height: 28px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .messages-container.skeleton-loading .new-chat-btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 36px;
      height: 36px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    /* Skeleton for search input */
    .messages-container.skeleton-loading .conversations-search input::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 20px;
    }

    /* Skeleton for conversation items */
    .messages-container.skeleton-loading .conversation-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 20px;
    }

    .messages-container.skeleton-loading .conversation-item::after {
      content: '';
      position: absolute;
      top: 16px;
      left: 20px;
      width: 50px;
      height: 50px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .messages-container.skeleton-loading .conversation-name::before {
      content: '';
      position: absolute;
      left: 82px;
      top: 20px;
      width: 120px;
      height: 16px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .messages-container.skeleton-loading .conversation-preview::before {
      content: '';
      position: absolute;
      left: 82px;
      top: 42px;
      width: 180px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for chat header */
    .messages-container.skeleton-loading .chat-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 24px;
    }

    .messages-container.skeleton-loading .chat-user-avatar::before {
      content: '';
      position: absolute;
      top: 16px;
      left: 24px;
      width: 44px;
      height: 44px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .messages-container.skeleton-loading .chat-user-name::before {
      content: '';
      position: absolute;
      left: 80px;
      top: 20px;
      width: 150px;
      height: 18px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .messages-container.skeleton-loading .chat-user-status::before {
      content: '';
      position: absolute;
      left: 80px;
      top: 42px;
      width: 80px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for chat actions */
    .messages-container.skeleton-loading .chat-actions::before {
      content: '';
      position: absolute;
      right: 24px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      gap: 8px;
    }

    .messages-container.skeleton-loading .chat-actions button::before {
      content: '';
      width: 36px;
      height: 36px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    /* Skeleton for messages */
    .messages-container.skeleton-loading .message::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      padding: 12px 16px;
      border-radius: 18px;
    }

    .messages-container.skeleton-loading .message.sent::before {
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
    }

    .messages-container.skeleton-loading .message.received::before {
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
    }

    /* Skeleton for chat input */
    .messages-container.skeleton-loading .chat-input::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 24px;
    }

    .messages-container.skeleton-loading .send-btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 44px;
      height: 44px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: 260px; height: 100vh;
      background: #fff;
      border-right: 1px solid var(--border);
      padding: 24px;
      display: flex; flex-direction: column; justify-content: space-between;
      overflow-y: auto;
    }
    .logo { display: flex; align-items: center; gap: 6px; font-weight: bold; font-size: 20px; margin-bottom: 20px; }
    .logo img { width: 28px; height: 28px; }
    .search-box {
      background: #f3f3f3; border-radius: 50px;
      padding: 10px 15px; display: flex; align-items: center;
      margin-bottom: 24px;
    }
    .search-box input {
      border: none; background: none; width: 100%;
      outline: none; font-size: 14px; color: var(--text-color);
    }
    .menu a {
      display: flex; align-items: center; gap: 12px;
      color: var(--text-color); text-decoration: none;
      padding: 10px 0; font-size: 15px; transition: 0.2s;
    }
    .menu a:hover, .menu a.active { color: var(--accent); font-weight: bold; }
    .menu i { font-size: 18px; width: 22px; text-align: center; }

    /* Messages Layout */
    .messages-container {
      margin-left: 260px;
      width: calc(100% - 260px);
      height: 100vh;
      display: flex;
      background: #fff;
      transition: opacity 0.3s ease;
    }

    .messages-container.skeleton-loading {
      opacity: 0.9;
    }

    .conversations-sidebar {
      width: 380px; height: 100vh;
      border-right: 1px solid var(--border);
      display: flex; flex-direction: column;
      background: #fff;
    }
    .messages-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex; justify-content: space-between; align-items: center;
    }
    .messages-title { font-size: 22px; font-weight: 700; }
    .new-chat-btn {
      background: var(--accent); color: white; border: none; border-radius: 50%;
      width: 36px; height: 36px; cursor: pointer; font-size: 16px;
    }
    .conversations-search { padding: 16px 24px; border-bottom: 1px solid var(--border); }
    .conversations-search input {
      width: 100%; padding: 12px 16px; border: 1px solid var(--border);
      border-radius: 20px; font-size: 14px; outline: none; background: var(--light-bg);
    }
    .conversation-list { flex: 1; overflow-y: auto; }
    .conversation-item {
      display: flex; align-items: center;
      padding: 16px 20px; border-bottom: 1px solid var(--border);
      cursor: pointer; transition: background 0.2s; gap: 12px;
      position: relative;
    }
    .conversation-item:hover { background: var(--light-bg); }
    .conversation-item.active { background: #fff5f7; border-right: 3px solid var(--accent); }
    .conversation-avatar {
      width: 50px; height: 50px; border-radius: 50%; object-fit: cover;
      border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .conversation-content { flex: 1; min-width: 0; }
    .conversation-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .conversation-name { font-weight: 600; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .conversation-time { font-size: 11px; color: var(--muted); white-space: nowrap; }
    .conversation-preview { font-size: 13px; color: var(--muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .unread-badge {
      background: var(--accent); color: white;
      border-radius: 10px; font-size: 11px; padding: 2px 6px;
      margin-left: auto;
    }

    /* Chat Area */
    .chat-area { flex: 1; display: flex; flex-direction: column; height: 100vh; }
    .chat-header {
      padding: 16px 24px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: 12px;
      position: relative;
    }
    .chat-user-avatar {
      width: 44px; height: 44px; border-radius: 50%; object-fit: cover;
      border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .chat-user-name { font-weight: 600; font-size: 16px; }
    .chat-user-status { font-size: 12px; color: var(--muted); }
    .chat-actions button {
      background: none; border: none; color: var(--muted); cursor: pointer;
      font-size: 18px; padding: 8px; border-radius: 50%; transition: 0.2s;
      position: relative;
    }
    .chat-actions button:hover { background: var(--light-bg); color: var(--accent); }

    /* Messages */
    .messages-area {
      flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column;
      gap: 16px; background: var(--light-bg);
    }
    .message {
      max-width: 70%; padding: 12px 16px; border-radius: 18px;
      word-wrap: break-word;
      position: relative;
    }
    .message.sent { align-self: flex-end; background: var(--accent); color: #fff; border-bottom-right-radius: 4px; }
    .message.received { align-self: flex-start; background: white; color: var(--text-color); border: 1px solid var(--border); border-bottom-left-radius: 4px; }
    .message-time { font-size: 11px; opacity: 0.7; margin-top: 4px; text-align: right; }

    /* Input */
    .chat-input-area {
      padding: 16px 24px; border-top: 1px solid var(--border);
      display: flex; align-items: center; gap: 12px; background: white;
    }
    .chat-input {
      flex: 1; padding: 12px 16px; border: 1px solid var(--border);
      border-radius: 24px; font-size: 14px; outline: none; resize: none;
      min-height: 44px; max-height: 120px;
      position: relative;
    }
    .send-btn {
      background: var(--accent); color: white; border: none;
      border-radius: 50%; width: 44px; height: 44px; font-size: 18px;
      cursor: pointer;
      position: relative;
    }
    /* Call Options Dropdown */
.call-options-dropdown {
    position: relative;
    display: inline-block;
}

.call-options-menu {
    display: none;
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 1000;
    min-width: 180px;
    margin-bottom: 10px;
}

.call-option-header {
    font-size: 11px;
    color: var(--muted);
    font-weight: 600;
    padding: 4px 8px;
    margin: 4px 0;
    text-transform: uppercase;
    border-bottom: 1px solid var(--light-bg);
}

.call-option {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 8px 12px;
    border: none;
    background: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    color: var(--text-color);
    transition: background 0.2s;
}

.call-option:hover {
    background: var(--light-bg);
}

.call-option i {
    width: 16px;
    text-align: center;
}
/* ADD THIS TO YOUR CSS */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    border-radius: 12px;
    position: relative;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
    color: var(--muted);
    z-index: 1001;
}

.modal-close:hover {
    color: var(--text-color);
}

.call-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.end-call {
    background: #ff0050;
    color: white;
}

.call-btn:hover {
    transform: scale(1.1);
}
/* Call Invitation Styles */
.call-invitation {
    max-width: 85% !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border: none !important;
}

.call-invite-content {
    text-align: center;
}

.call-invite-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
    margin-bottom: 8px;
}

.call-invite-header i {
    font-size: 16px;
}

.join-call-btn {
    background: #00d26a;
    color: white;
    border: none;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin: 8px 0;
    transition: all 0.3s;
}

.join-call-btn:hover {
    background: #00b359;
    transform: scale(1.05);
}

.call-invite-link {
    margin: 8px 0;
    font-size: 12px;
}

.call-invite-link a {
    color: #a0a0ff;
    text-decoration: none;
    word-break: break-all;
}

.call-invite-link a:hover {
    text-decoration: underline;
}

.call-invite-note {
    font-size: 11px;
    opacity: 0.8;
    margin-top: 4px;
}
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div>
      <div class="logo">
        <img src="{{ asset('image/snipsnap.png') }}" alt="SnipSnap"> SnipSnap
      </div>

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search">
      </div>

      <div class="menu">
        <a href="{{ route('my-web') }}"><i class="fa-solid fa-house"></i>For You</a>
        <a href="{{ route('explore.users') }}"><i class="fa-regular fa-compass"></i>Explore</a>
        <a href="{{ route('following.videos') }}"><i class="fa-solid fa-user-group"></i>Following</a>
        <a href="{{ route('friends') }}"><i class="fa-solid fa-user-friends"></i>Friends</a>
        <a href="{{ route('upload') }}"><i class="fa-solid fa-plus-square"></i>Upload</a>
        <a href="{{ route('notifications') }}"><i class="fa-regular fa-comment-dots"></i>Notifications</a>
        <a href="#" class="active"><i class="fa-regular fa-paper-plane"></i>Messages</a>
        <a href="#"><i class="fa-solid fa-tv"></i>LIVE</a>
        <a href="{{ route('profile.show') }}"><i class="fa-solid fa-user"></i>Profile</a>
        <a href="#"><i class="fa-solid fa-ellipsis"></i>More</a>
      </div>
    </div>

    <form method="POST" action="{{ route('logout.perform') }}">
      @csrf
      <button style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:14px;">Logout</button>
    </form>
  </aside>

  <!-- Messages Container -->
  <div class="messages-container" id="messagesContainer">

    <!-- Conversations Sidebar -->
    <div class="conversations-sidebar">
      <div class="messages-header">
        <h2 class="messages-title">Messages</h2>
        <button class="new-chat-btn"><i class="fa-solid fa-edit"></i></button>
      </div>

      <div class="conversations-search">
        <input type="text" placeholder="Search messages..." id="searchConversations">
      </div>

      <!-- Conversation List -->
      <div class="conversation-list">
        @foreach ($conversationUsers as $friend)
          <div class="conversation-item {{ ($currentConversationUser->id ?? '') === $friend->id ? 'active' : '' }}"
               onclick="window.location='{{ route('messages.show', $friend->id) }}'">

            <!-- Avatar -->
            <img src="{{ !empty($friend->avatar) ? asset('storage/' . $friend->avatar) : asset('image/default-avatar.png') }}"
                 alt="{{ $friend->name }}" class="conversation-avatar">

            <!-- Conversation Info -->
            <div class="conversation-content">
              <div class="conversation-header">
                <span class="conversation-name">{{ $friend->name }}</span>
                <span class="conversation-time">
                  {{ optional($friend->lastMessage)->created_at ? $friend->lastMessage->created_at->diffForHumans() : '' }}
                </span>
              </div>
              <div class="conversation-preview">
                {{ \Illuminate\Support\Str::limit(optional($friend->lastMessage)->content ?? 'No messages yet', 50) }}
              </div>
            </div>

            @if(optional($friend->lastMessage)->isUnread())
              <div class="unread-badge">{{ $friend->lastMessage->unread_count ?? '' }}</div>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
      <div class="chat-header">
        <img src="{{ !empty($currentConversationUser->avatar) ? asset('storage/' . $currentConversationUser->avatar) : asset('image/default-avatar.png') }}"
             alt="{{ $currentConversationUser->name ?? '' }}" class="chat-user-avatar">
        <div class="chat-user-info">
          <div class="chat-user-name">{{ $currentConversationUser->name ?? 'Select a conversation' }}</div>
          <div class="chat-user-status">
            {{ isset($currentConversationUser) && method_exists($currentConversationUser, 'isOnline') && $currentConversationUser->isOnline() ? 'Online' : 'Offline' }}
          </div>
        </div>
        <div class="chat-actions">
    <button class="chat-action-btn" onclick="toggleUserInfo()">
        <i class="fa-solid fa-circle-info"></i>
    </button>
    
    <!-- SIMPLE TEST BUTTONS - These will work! -->
    <button class="chat-action-btn" onclick="startJitsiCall('video')" style="background: #00d26a; color: white;">
        <i class="fas fa-video"></i>
    </button>
    
    <button class="chat-action-btn" onclick="startJitsiCall('voice')" style="background: #ff0050; color: white;">
        <i class="fas fa-phone"></i>
    </button>
</div>
      </div>

      <div class="messages-area" id="messagesArea">
        @forelse($messages as $message)
          <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
            <div class="message-text">{{ $message->content }}</div>
            <div class="message-time">{{ $message->created_at->format('h:i A') }}</div>
          </div>
        @empty
          <div class="empty-chat">
            <i class="fa-regular fa-message"></i>
            <h3>No messages yet</h3>
            <p>Select a friend or start a new conversation.</p>
          </div>
        @endforelse
      </div>

      <div class="chat-input-area">
        <textarea class="chat-input" placeholder="Type a message..." rows="1" id="messageInput" oninput="autoResize(this)"></textarea>
        <button class="send-btn" id="sendBtn" onclick="sendMessage()" disabled><i class="fa-solid fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
  <!-- Add this before closing </body> tag -->
<script src='https://meet.jit.si/external_api.js'></script>

<!-- Updated Call Modal for Jitsi -->
<div id="callModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 95%; width: 1000px; height: 90vh; padding: 0;">
        <div class="call-header" style="padding: 15px 20px; border-bottom: 1px solid var(--border);">
            <h3 id="callStatus">Starting Call...</h3>
            <div class="call-timer" id="callTimer">00:00</div>
            <button class="call-btn end-call" onclick="endJitsiCall()" style="position: absolute; right: 20px; top: 15px;">
                <i class="fas fa-phone-slash"></i>
            </button>
        </div>
        <div id="jitsiContainer" style="width: 100%; height: calc(100% - 70px);"></div>
    </div>
</div>

<!-- Keep existing PeerJS modals, just rename them -->
<div id="peerCallModal" class="modal" style="display: none;">
    <!-- Your existing PeerJS call modal content -->
    <div class="modal-content" style="max-width: 90%; width: 800px;">
        <span class="modal-close" onclick="endCall()">&times;</span>
        
        <div class="call-header">
            <h3 id="peerCallStatus">Calling {{ $currentConversationUser->name ?? 'User' }}...</h3>
            <div class="call-timer" id="peerCallTimer">00:00</div>
        </div>
        
        <div class="video-container">
            <div id="remoteVideoContainer" style="width: 100%; height: 400px; background: #000; border-radius: 8px; margin-bottom: 10px;">
                <video id="remoteVideo" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
            </div>
            <div id="localVideoContainer" style="width: 200px; height: 150px; background: #000; border-radius: 8px; position: absolute; bottom: 20px; right: 20px;">
                <video id="localVideo" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: cover;"></video>
            </div>
        </div>
        
        <div class="call-controls">
            <button class="call-btn end-call" onclick="endCall()">
                <i class="fas fa-phone-slash"></i>
            </button>
            <button class="call-btn mute-btn" onclick="toggleMute()">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="call-btn video-btn" onclick="toggleVideo()">
                <i class="fas fa-video"></i>
            </button>
        </div>
    </div>
</div>

<!-- Incoming Call Modal (for PeerJS) -->
<div id="incomingCallModal" class="modal" style="display: none;">
    <div class="modal-content" style="text-align: center;">
        <div class="incoming-call-avatar">
            <img src="{{ !empty($currentConversationUser->avatar) ? asset('storage/' . $currentConversationUser->avatar) : asset('image/default-avatar.png') }}" 
                 alt="Caller" style="width: 80px; height: 80px; border-radius: 50%;">
        </div>
        <h3 id="incomingCallName">Incoming Call</h3>
        <p id="incomingCallType">Video Call</p>
        
        <div class="incoming-call-controls">
            <button class="call-btn accept-call" id="acceptCallBtn">
                <i class="fas fa-phone"></i>
            </button>
            <button class="call-btn decline-call" id="declineCallBtn">
                <i class="fas fa-phone-slash"></i>
            </button>
        </div>
    </div>
</div>

  <script>
    // ===== SKELETON LOADER FUNCTIONS =====
    let loaderTimeout;

    // Show skeleton loading
    function showSkeleton() {
      const messagesContainer = document.getElementById('messagesContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (messagesContainer) {
        messagesContainer.classList.add('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.add('skeleton-loading');
      }
    }

    // Hide skeleton loading
    function hideSkeleton() {
      const messagesContainer = document.getElementById('messagesContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (messagesContainer) {
        messagesContainer.classList.remove('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.remove('skeleton-loading');
      }
    }

    // Initialize skeleton loader on page load
    function initSkeletonLoader() {
      // Show skeleton immediately
      showSkeleton();
      
      // Hide loader after content is loaded (simulate loading time)
      setTimeout(() => {
        hideSkeleton();
      }, 2000); // 2 seconds loading time
    }

    // Enhanced navigation with skeleton loaders
    function initNavigation() {
      const sidebarLinks = document.querySelectorAll('.menu a');
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          if (this.classList.contains('active') || this.getAttribute('href') === '#') {
            return;
          }
          
          // Show skeleton for navigation
          showSkeleton();
          
          // Set timeout to hide loader (in case navigation is slow)
          loaderTimeout = setTimeout(() => {
            hideSkeleton();
          }, 3000);
        });
      });
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
      initSkeletonLoader();
      initNavigation();
    });

    let currentConversationId = {{ $currentConversationUser->id ?? 0 }};
    
    function autoResize(t) {
      t.style.height = 'auto';
      t.style.height = Math.min(t.scrollHeight, 120) + 'px';
      document.getElementById('sendBtn').disabled = t.value.trim() === '';
    }

    async function sendMessage() {
      const input = document.getElementById('messageInput');
      const message = input.value.trim();
      if (!message || !currentConversationId) return;

      console.log('Sending message:', { message, receiver_id: currentConversationId });

      try {
        // Send to server first
        const response = await fetch(`/messages/send`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ 
            message: message, 
            receiver_id: currentConversationId 
          })
        });

        console.log('Response status:', response.status);
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.id) { // Check if message was created successfully
          // Only add to UI if server confirms success
          const messagesArea = document.getElementById('messagesArea');
          const div = document.createElement('div');
          div.className = 'message sent';
          div.innerHTML = `
            <div class="message-text">${message}</div>
            <div class="message-time">${new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>
          `;
          messagesArea.appendChild(div);
          messagesArea.scrollTop = messagesArea.scrollHeight;

          // Clear input
          input.value = ''; 
          input.style.height = 'auto';
          document.getElementById('sendBtn').disabled = true;

          // Reload the page after a short delay to sync with database
          setTimeout(() => {
            window.location.reload();
          }, 300);
        } else {
          alert('Failed to send message: ' + (data.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error sending message:', error);
        alert('Error sending message: ' + error.message);
      }
    }

    // Add Enter key listener
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
      if(e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });

    // Scroll to bottom on load
    document.addEventListener('DOMContentLoaded', function() {
      const messagesArea = document.getElementById('messagesArea');
      if(messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
      }
    });
    // Jitsi Meet Integration - 100% FREE
let jitsiApi;
let jitsiCallStartTime;
let jitsiCallTimerInterval;

// Start Jitsi call (more reliable, supports groups)
function startJitsiCall(type = 'video') {
    const roomName = generateJitsiRoomId();
    const displayName = '{{ Auth::user()->name }}';
    const userAvatar = '{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('image/default-avatar.png') }}';
    
    console.log('🚀 Starting Jitsi call:', roomName);
    
    // Show Jitsi modal
    document.getElementById('callModal').style.display = 'flex';
    document.getElementById('callStatus').textContent = 'Starting call...';
    
    // Generate invite link to share
    const inviteLink = `${window.location.origin}/call/join/${roomName}`;
    
    // Send Jitsi call invitation via your messaging system
    sendJitsiInvitation(roomName, type, inviteLink);
    
    // Initialize Jitsi
    initializeJitsiMeeting(roomName, displayName, userAvatar, type);
}

// Generate unique Jitsi room ID
function generateJitsiRoomId() {
    return `snipsnap_{{ Auth::id() }}_${currentConversationId}_${Date.now()}`;
}

// Send Jitsi call invitation
async function sendJitsiInvitation(roomName, type, inviteLink) {
    try {
        // Send as a special call invitation
        const response = await fetch('/messages/call-invitation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receiver_id: currentConversationId,
                room_name: roomName,
                call_type: type,
                invite_link: inviteLink
            })
        });
        
        const data = await response.json();
        if (data.success) {
            console.log('✅ Call invitation sent');
            
            // Also add the invitation to the chat UI immediately
            addCallInvitationToChat(type, inviteLink, true);
        }
        
    } catch (error) {
        console.error('Error sending call invitation:', error);
        // Fallback: send as regular message
        sendFallbackInvitation(type, inviteLink);
    }
}

// Fallback: send as regular message
async function sendFallbackInvitation(type, inviteLink) {
    try {
        const message = `📞 I started a ${type} call. Join me: ${inviteLink}`;
        
        await fetch('/messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receiver_id: currentConversationId,
                message: message
            })
        });
        
        console.log('✅ Fallback invitation sent');
    } catch (error) {
        console.error('Error sending fallback invitation:', error);
    }
}

// Add call invitation to chat UI
function addCallInvitationToChat(type, inviteLink, isSender = false) {
    const messagesArea = document.getElementById('messagesArea');
    const invitationDiv = document.createElement('div');
    
    if (isSender) {
        invitationDiv.className = 'message sent call-invitation';
        invitationDiv.innerHTML = `
            <div class="call-invite-content">
                <div class="call-invite-header">
                    <i class="fas fa-${type === 'video' ? 'video' : 'phone'}"></i>
                    <span>You started a ${type} call</span>
                </div>
                <div class="call-invite-link">
                    <a href="${inviteLink}" target="_blank">${inviteLink}</a>
                </div>
                <div class="call-invite-note">Share this link to invite others</div>
            </div>
            <div class="message-time">${new Date().toLocaleTimeString()}</div>
        `;
    } else {
        invitationDiv.className = 'message received call-invitation';
        invitationDiv.innerHTML = `
            <div class="call-invite-content">
                <div class="call-invite-header">
                    <i class="fas fa-${type === 'video' ? 'video' : 'phone'}"></i>
                    <span>Incoming ${type} call</span>
                </div>
                <button class="join-call-btn" onclick="joinJitsiCallFromInvitation('${inviteLink}')">
                    <i class="fas fa-phone"></i> Join Call
                </button>
                <div class="call-invite-link">
                    <a href="${inviteLink}" target="_blank">Or click here to join</a>
                </div>
            </div>
            <div class="message-time">${new Date().toLocaleTimeString()}</div>
        `;
    }
    
    messagesArea.appendChild(invitationDiv);
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

// Join call from invitation
function joinJitsiCallFromInvitation(inviteLink) {
    // Extract room name from invite link
    const roomName = extractRoomNameFromLink(inviteLink);
    if (roomName) {
        joinJitsiCall(roomName);
    } else {
        // Open in new tab as fallback
        window.open(inviteLink, '_blank');
    }
}

function extractRoomNameFromLink(inviteLink) {
    try {
        const url = new URL(inviteLink);
        const path = url.pathname;
        // Extract room name from /call/join/ROOM_NAME
        const match = path.match(/\/call\/join\/(.+)/);
        return match ? match[1] : null;
    } catch (error) {
        return null;
    }
}


// Initialize Jitsi meeting
function initializeJitsiMeeting(roomName, displayName, userAvatar, type) {
    const domain = 'meet.jit.si'; // Free Jitsi instance
    const options = {
        roomName: roomName,
        width: '100%',
        height: '100%',
        parentNode: document.getElementById('jitsiContainer'),
        userInfo: {
            displayName: displayName,
            // avatarUrl: userAvatar // Jitsi free version may not support custom avatars
        },
        configOverwrite: {
            prejoinPageEnabled: false,
            startWithAudioMuted: false,
            startWithVideoMuted: type === 'voice',
            disableModeratorIndicator: true,
            startScreenSharing: false,
            enableEmailInStats: false,
            enableWelcomePage: false,
            enableClosePage: false,
            defaultLanguage: 'en',
            disableThirdPartyRequests: true,
            enableNoAudioDetection: true,
            enableNoisyMicDetection: true,
            resolution: 720,
            constraints: {
                video: {
                    height: { ideal: 720, max: 1080, min: 240 }
                }
            }
        },
        interfaceConfigOverwrite: {
            TOOLBAR_BUTTONS: [
                'microphone', 'camera', 'closedcaptions', 'desktop', 'embedmeeting', 'fullscreen',
                'fodeviceselection', 'hangup', 'profile', 'chat', 'recording', 'livestreaming',
                'etherpad', 'sharedvideo', 'settings', 'raisehand', 'videoquality', 'filmstrip',
                'invite', 'feedback', 'stats', 'shortcuts', 'tileview', 'videobackgroundblur',
                'download', 'help', 'mute-everyone', 'mute-video-everyone', 'security'
            ],
            SETTINGS_SECTIONS: ['devices', 'language', 'moderator', 'profile', 'calendar'],
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            SHOW_BRAND_WATERMARK: false,
            BRAND_WATERMARK_LINK: '',
            SHOW_POWERED_BY: false,
            SHOW_PROMOTIONAL_CLOSE_PAGE: false,
            SHOW_CHROME_EXTENSION_BANNER: false,
            MOBILE_APP_PROMO: false,
            HIDE_INVITE_MORE_HEADER: false
        }
    };

    try {
        jitsiApi = new JitsiMeetExternalAPI(domain, options);
        
        // Jitsi event listeners
        jitsiApi.addEventListener('videoConferenceJoined', (participant) => {
            console.log('✅ Joined Jitsi conference');
            document.getElementById('callStatus').textContent = 'Call Connected';
            startJitsiCallTimer();
        });

        jitsiApi.addEventListener('videoConferenceLeft', () => {
            console.log('❌ Left Jitsi conference');
            endJitsiCall();
        });

        jitsiApi.addEventListener('participantJoined', (participant) => {
            console.log('👤 Participant joined:', participant);
            document.getElementById('callStatus').textContent = `${participant.displayName} joined`;
        });

        jitsiApi.addEventListener('participantLeft', (participant) => {
            console.log('👤 Participant left:', participant);
            document.getElementById('callStatus').textContent = `${participant.displayName} left`;
        });

        jitsiApi.addEventListener('audioMuteStatusChanged', (muted) => {
            console.log('🎤 Audio mute:', muted);
        });

        jitsiApi.addEventListener('videoMuteStatusChanged', (muted) => {
            console.log('📹 Video mute:', muted);
        });

    } catch (error) {
        console.error('❌ Jitsi initialization error:', error);
        document.getElementById('callStatus').textContent = 'Failed to start call';
        setTimeout(() => {
            endJitsiCall();
        }, 3000);
    }
}

// End Jitsi call
function endJitsiCall() {
    console.log('🛑 Ending Jitsi call');
    
    if (jitsiApi) {
        jitsiApi.dispose();
        jitsiApi = null;
    }
    
    document.getElementById('callModal').style.display = 'none';
    document.getElementById('jitsiContainer').innerHTML = '';
    stopJitsiCallTimer();
    
    console.log('✅ Jitsi call ended');
}

// Jitsi call timer
function startJitsiCallTimer() {
    jitsiCallStartTime = new Date();
    jitsiCallTimerInterval = setInterval(updateJitsiCallTimer, 1000);
}

function stopJitsiCallTimer() {
    if (jitsiCallTimerInterval) {
        clearInterval(jitsiCallTimerInterval);
        jitsiCallTimerInterval = null;
    }
    document.getElementById('callTimer').textContent = '00:00';
}

function updateJitsiCallTimer() {
    const now = new Date();
    const diff = Math.floor((now - jitsiCallStartTime) / 1000);
    const minutes = Math.floor(diff / 60).toString().padStart(2, '0');
    const seconds = (diff % 60).toString().padStart(2, '0');
    document.getElementById('callTimer').textContent = `${minutes}:${seconds}`;
}

// Join Jitsi call from invitation link
function joinJitsiCall(roomName) {
    const displayName = '{{ Auth::user()->name }}';
    document.getElementById('callModal').style.display = 'flex';
    initializeJitsiMeeting(roomName, displayName, '', 'video');
}
// ADD THIS FUNCTION TO YOUR JAVASCRIPT
function testCallFunctions() {
    console.log('=== CALL SYSTEM TEST ===');
    console.log('currentConversationId:', currentConversationId);
    console.log('startJitsiCall function:', typeof startJitsiCall);
    console.log('Jitsi available:', typeof JitsiMeetExternalAPI);
    
    if (!currentConversationId || currentConversationId === 0) {
        alert('Please select a conversation first!');
        return false;
    }
    
    if (typeof startJitsiCall !== 'function') {
        alert('Call system not loaded. Refresh the page.');
        return false;
    }
    
    return true;
}

// UPDATE YOUR JITSI FUNCTION TO INCLUDE THE TEST
function startJitsiCall(type = 'video') {
    // Test if everything is working
    if (!testCallFunctions()) return;
    
    const roomName = generateJitsiRoomId();
    const displayName = '{{ Auth::user()->name }}';
    
    console.log('🚀 Starting Jitsi call:', roomName);
    
    // Show Jitsi modal
    document.getElementById('callModal').style.display = 'flex';
    document.getElementById('callStatus').textContent = 'Starting call...';
    
    // Initialize Jitsi immediately (skip invitation for testing)
    initializeJitsiMeeting(roomName, displayName, '', type);
    
    // For testing, also send invitation
    setTimeout(() => {
        const inviteLink = `${window.location.origin}/call/join/${roomName}`;
        sendJitsiInvitation(roomName, type, inviteLink);
    }, 1000);
}
</script>
</body>
</html>