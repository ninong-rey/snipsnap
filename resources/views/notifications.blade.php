<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SnipSnap - Notifications</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --accent: #ff0050;
      --text-color: #000;
      --muted: #666;
      --light-bg: #f8f8f8;
      --border: #eee;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
      color: var(--text-color);
      overflow: hidden;
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

    /* ==== NOTIFICATIONS CONTENT SKELETON ==== */
    .notifications-container.skeleton-loading .notifications-title,
    .notifications-container.skeleton-loading .mark-all-read,
    .notifications-container.skeleton-loading .tab,
    .notifications-container.skeleton-loading .notification-item,
    .notifications-container.skeleton-loading .empty-state > * {
      position: relative;
    }

    /* Hide actual content during skeleton loading */
    .notifications-container.skeleton-loading .notifications-title,
    .notifications-container.skeleton-loading .mark-all-read,
    .notifications-container.skeleton-loading .tab,
    .notifications-container.skeleton-loading .notification-icon,
    .notifications-container.skeleton-loading .notification-avatar,
    .notifications-container.skeleton-loading .notification-text,
    .notifications-container.skeleton-loading .notification-time,
    .notifications-container.skeleton-loading .notification-preview,
    .notifications-container.skeleton-loading .notification-actions,
    .notifications-container.skeleton-loading .notification-media,
    .notifications-container.skeleton-loading .empty-state i,
    .notifications-container.skeleton-loading .empty-state h3,
    .notifications-container.skeleton-loading .empty-state p {
      opacity: 0;
    }

    /* Skeleton for header */
    .notifications-container.skeleton-loading .notifications-title::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 200px;
      height: 28px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .notifications-container.skeleton-loading .mark-all-read::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 120px;
      height: 20px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for tabs */
    .notifications-container.skeleton-loading .notifications-tabs::before {
      content: '';
      display: flex;
      gap: 20px;
    }

    .notifications-container.skeleton-loading .tab::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80px;
      height: 20px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for notification items */
    .notifications-container.skeleton-loading .notification-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px 20px;
    }

    .notifications-container.skeleton-loading .notification-item::after {
      content: '';
      position: absolute;
      top: 20px;
      left: 20px;
      width: 20px;
      height: 20px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .notifications-container.skeleton-loading .notification-avatar::before {
      content: '';
      position: absolute;
      top: 16px;
      left: 52px;
      width: 44px;
      height: 44px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .notifications-container.skeleton-loading .notification-content::before {
      content: '';
      position: absolute;
      left: 108px;
      top: 20px;
      width: 60%;
      height: 14px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .notifications-container.skeleton-loading .notification-content::after {
      content: '';
      position: absolute;
      left: 108px;
      top: 42px;
      width: 100px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .notifications-container.skeleton-loading .notification-actions::before {
      content: '';
      position: absolute;
      left: 108px;
      top: 62px;
      width: 80px;
      height: 24px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .notifications-container.skeleton-loading .notification-media::before {
      content: '';
      position: absolute;
      top: 16px;
      right: 20px;
      width: 50px;
      height: 70px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for empty state */
    .notifications-container.skeleton-loading .empty-state::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .notifications-container.skeleton-loading .empty-state::after {
      content: '';
      width: 64px;
      height: 64px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
      margin-bottom: 16px;
    }

    .notifications-container.skeleton-loading .empty-state h3::before {
      content: '';
      width: 200px;
      height: 18px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
      margin-bottom: 8px;
    }

    .notifications-container.skeleton-loading .empty-state p::before {
      content: '';
      width: 300px;
      height: 14px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    /* Sidebar - Same as your other pages */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100vh;
      background: #fff;
      border-right: 1px solid var(--border);
      padding: 24px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 20px;
      color: var(--text-color);
    }

    .logo img { width: 28px; height: 28px; }

    .search-box {
      background: #f3f3f3;
      border-radius: 50px;
      padding: 10px 15px;
      display: flex;
      align-items: center;
      margin-bottom: 24px;
    }

    .search-box input {
      border: none;
      background: none;
      width: 100%;
      outline: none;
      font-size: 14px;
      color: var(--text-color);
    }

    .menu { flex-grow: 1; }

    .menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--text-color);
      text-decoration: none;
      padding: 10px 0;
      font-size: 15px;
      transition: 0.2s;
    }

    .menu a:hover,
    .menu a.active {
      color: var(--accent);
      font-weight: bold;
    }

    .menu i {
      font-size: 18px;
      width: 22px;
      text-align: center;
    }

    .notification-dot {
      background: var(--accent);
      color: #fff;
      border-radius: 50%;
      font-size: 10px;
      padding: 2px 5px;
      margin-left: auto;
    }

    /* Notifications Content - TikTok Style */
    .notifications-container {
      margin-left: 260px;
      width: calc(100% - 260px);
      height: 100vh;
      overflow-y: auto;
      background: #fff;
      transition: opacity 0.3s ease;
    }

    .notifications-container.skeleton-loading {
      opacity: 0.9;
    }

    .notifications-header {
      padding: 20px 24px;
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 10;
      border-bottom: 1px solid var(--border);
    }

    .notifications-title {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 16px;
    }

    /* TikTok-style Tabs */
    .notifications-tabs {
      display: flex;
      gap: 0;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
      border-bottom: 1px solid var(--border);
    }

    .notifications-tabs::-webkit-scrollbar {
      display: none;
    }

    .tab {
      padding: 12px 20px;
      font-weight: 600;
      color: var(--muted);
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
      white-space: nowrap;
      font-size: 15px;
      position: relative;
    }

    .tab.active {
      color: var(--text-color);
      border-bottom: 2px solid var(--text-color);
    }

    .tab-badge {
      background: var(--accent);
      color: white;
      border-radius: 10px;
      font-size: 11px;
      padding: 2px 6px;
      margin-left: 6px;
    }

    /* Notifications List */
    .notifications-list {
      padding: 0;
    }

    .notification-item {
      display: flex;
      align-items: flex-start;
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      transition: background 0.2s;
      cursor: pointer;
      gap: 12px;
      position: relative;
    }

    .notification-item:hover {
      background: #fafafa;
    }

    .notification-item.unread {
      background: #fef5f7;
    }

    .notification-item.unread:hover {
      background: #fdeff2;
    }

    .notification-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      flex-shrink: 0;
    }

    .notification-content {
      flex: 1;
      min-width: 0;
      position: relative;
    }

    .notification-text {
      font-size: 14px;
      line-height: 1.4;
      margin-bottom: 4px;
    }

    .notification-user {
      font-weight: 600;
      color: var(--text-color);
    }

    .notification-time {
      font-size: 12px;
      color: var(--muted);
    }

    .notification-preview {
      color: var(--muted);
      font-size: 13px;
      margin-top: 4px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .notification-media {
      width: 50px;
      height: 70px;
      border-radius: 4px;
      object-fit: cover;
      flex-shrink: 0;
      margin-left: 8px;
    }

    .notification-icon {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 8px;
      flex-shrink: 0;
    }

    .icon-heart {
      background: #ffe6ec;
      color: var(--accent);
    }

    .icon-comment {
      background: #e6f3ff;
      color: #0095f6;
    }

    .icon-message {
      background: #f0f0f0;
      color: var(--text-color);
    }

    .icon-follow {
      background: #e6f7e6;
      color: #00a400;
    }

    .icon-share {
      background: #fff0e6;
      color: #ff6b00;
    }

    /* Notification Actions */
    .notification-actions {
      display: flex;
      gap: 8px;
      margin-top: 8px;
      position: relative;
    }

    .btn-small {
      padding: 4px 12px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }

    .btn-follow {
      background: var(--accent);
      color: white;
    }

    .btn-follow:hover {
      background: #e00040;
    }

    .btn-reply {
      background: var(--light-bg);
      color: var(--text-color);
      border: 1px solid var(--border);
    }

    .btn-reply:hover {
      background: #e8e8e8;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      position: relative;
    }

    .empty-state i {
      font-size: 64px;
      color: var(--muted);
      margin-bottom: 16px;
    }

    .empty-state h3 {
      font-size: 18px;
      margin-bottom: 8px;
      color: var(--text-color);
      font-weight: 600;
    }

    .empty-state p {
      color: var(--muted);
      margin-bottom: 20px;
      max-width: 300px;
      margin-left: auto;
      margin-right: auto;
      font-size: 14px;
      line-height: 1.4;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .sidebar {
        display: none;
      }
      .notifications-container {
        margin-left: 0;
        width: 100%;
      }
    }

    /* Mark all read button */
    .mark-all-read {
      background: none;
      border: none;
      color: var(--accent);
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      margin-left: auto;
      position: relative;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div>
      <div class="logo">
        <img src="{{ asset('image/snipsnap.png') }}" alt="SnipSnap">
        SnipSnap
      </div>

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search...">
      </div>

      <div class="menu">
        <a href="{{ route('my-web') }}"><i class="fa-solid fa-house"></i>For You</a>
        <a href="{{ route('explore.users') }}"><i class="fa-regular fa-compass"></i>Explore</a>
        <a href="{{ route('following.videos') }}"><i class="fa-solid fa-user-group"></i>Following</a>
        <a href="{{ route('friends') }}"><i class="fa-solid fa-user-friends"></i>Friends</a>
        <a href="{{ route('upload') }}"><i class="fa-solid fa-plus-square"></i>Upload</a>
        <a href="{{ route('notifications') }}" class="active"><i class="fa-regular fa-comment-dots"></i>Notifications 
          @if($unreadCount > 0)
            <span class="notification-dot">{{ $unreadCount }}</span>
          @endif
        </a>
        <a href="{{ route('messages.index') }}"><i class="fa-regular fa-paper-plane"></i>Messages</a>
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

  <!-- Notifications Content -->
  <div class="notifications-container" id="notificationsContainer">
    <div class="notifications-header">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1 class="notifications-title">Notifications</h1>
        @if($unreadCount > 0)
          <button class="mark-all-read" onclick="markAllAsRead()">
            <i class="fa-solid fa-check-double"></i> Mark all as read
          </button>
        @endif
      </div>
      
      <!-- TikTok-style Tabs -->
      <div class="notifications-tabs">
        <div class="tab {{ $tab == 'all' ? 'active' : '' }}" data-tab="all">All</div>
        <div class="tab {{ $tab == 'likes' ? 'active' : '' }}" data-tab="likes">
          Likes 
          @if($likeCount > 0)
            <span class="tab-badge">{{ $likeCount }}</span>
          @endif
        </div>
        <div class="tab {{ $tab == 'comments' ? 'active' : '' }}" data-tab="comments">
          Comments 
          @if($commentCount > 0)
            <span class="tab-badge">{{ $commentCount }}</span>
          @endif
        </div>
        <div class="tab {{ $tab == 'follows' ? 'active' : '' }}" data-tab="follows">
          Follows 
          @if($followCount > 0)
            <span class="tab-badge">{{ $followCount }}</span>
          @endif
        </div>
      </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
      @if($notifications->count() > 0)
        @foreach($notifications as $notification)
          <div class="notification-item {{ $notification->read ? '' : 'unread' }}" 
               data-notification-id="{{ $notification->id }}"
               onclick="markAsRead({{ $notification->id }}, this)">
            <div class="notification-icon icon-{{ $notification->type }}">
              @if($notification->type == 'like')
                <i class="fa-solid fa-heart"></i>
              @elseif($notification->type == 'comment')
                <i class="fa-solid fa-comment"></i>
              @elseif($notification->type == 'follow')
                <i class="fa-solid fa-user-plus"></i>
              @elseif($notification->type == 'share')
                <i class="fa-solid fa-share"></i>
              @else
                <i class="fa-solid fa-bell"></i>
              @endif
            </div>
            
            <img src="{{ $notification->fromUser->avatar ? asset('storage/' . $notification->fromUser->avatar) : asset('image/default-avatar.png') }}" 
                 alt="{{ $notification->fromUser->name }}" class="notification-avatar"
                 onclick="event.stopPropagation(); goToUserProfile('{{ $notification->fromUser->username ?? $notification->fromUser->id }}');">
            
            <div class="notification-content">
              <div class="notification-text">
                <span class="notification-user" 
                      onclick="event.stopPropagation(); goToUserProfile('{{ $notification->fromUser->username ?? $notification->fromUser->id }}');">
                  @{{ $notification->fromUser->username ?? $notification->fromUser->name }}
                </span> 
                {{ $notification->message }}
              </div>
              <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
              
              @if($notification->video)
                <div class="notification-preview">{{ $notification->video->caption ?? 'Check out this video' }}</div>
              @endif
              
              <div class="notification-actions">
                @if($notification->type == 'follow')
                  <button class="btn-small btn-follow" onclick="event.stopPropagation(); followUser({{ $notification->fromUser->id }}, this);">
                    <i class="fa-solid fa-user-plus"></i> Follow back
                  </button>
                @elseif($notification->type == 'comment' && $notification->video)
                  <button class="btn-small btn-reply" onclick="event.stopPropagation(); viewVideo({{ $notification->video->id }});">
                    <i class="fa-solid fa-eye"></i> View video
                  </button>
                @elseif($notification->type == 'like' && $notification->video)
                  <button class="btn-small btn-reply" onclick="event.stopPropagation(); viewVideo({{ $notification->video->id }});">
                    <i class="fa-solid fa-eye"></i> View video
                  </button>
                @endif
              </div>
            </div>
            
            @if($notification->video && $notification->video->thumbnail_url)
              <img src="{{ $notification->video->thumbnail_url }}" 
                   alt="Video thumbnail" 
                   class="notification-media"
                   onclick="event.stopPropagation(); viewVideo({{ $notification->video->id }});">
            @elseif($notification->video && $notification->video->url)
              <img src="{{ $notification->video->thumbnail_url }}"
                   alt="Video thumbnail" 
                   class="notification-media"
                   onclick="event.stopPropagation(); viewVideo({{ $notification->video->id }});">
            @endif
          </div>
        @endforeach
      @else
        <!-- Empty State (Explore Users button removed) -->
        <div class="empty-state">
          <i class="fa-regular fa-bell"></i>
          <h3>No Notifications Yet</h3>
          <p>When you get likes, comments, or new followers, they'll appear here.</p>
        </div>
      @endif
    </div>
  </div>

  <script>
    // ===== SKELETON LOADER FUNCTIONS =====
    let loaderTimeout;

    // Show skeleton loading
    function showSkeleton() {
      const notificationsContainer = document.getElementById('notificationsContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (notificationsContainer) {
        notificationsContainer.classList.add('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.add('skeleton-loading');
      }
    }

    // Hide skeleton loading
    function hideSkeleton() {
      const notificationsContainer = document.getElementById('notificationsContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (notificationsContainer) {
        notificationsContainer.classList.remove('skeleton-loading');
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

    // Tab functionality
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        const tabType = this.dataset.tab;
        window.location.href = `{{ route('notifications') }}?tab=${tabType}`;
      });
    });

    // Mark as read on click
    function markAsRead(notificationId, element) {
      if (element.classList.contains('unread')) {
        fetch(`/notifications/${notificationId}/read`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        }).then(response => response.json())
          .then(data => {
            if (data.success) {
              element.classList.remove('unread');
              updateUnreadCounts();
            }
          });
      }
    }

    function markAllAsRead() {
      fetch(`/notifications/read-all`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
              item.classList.remove('unread');
            });
            updateUnreadCounts();
          }
        });
    }

    function updateUnreadCounts() {
      fetch(`/notifications/unread-counts`)
        .then(response => response.json())
        .then(data => {
          // Sidebar dot
          const sidebarBadge = document.querySelector('.menu a.active .notification-dot');
          if (data.unread > 0) {
            if (sidebarBadge) {
              sidebarBadge.textContent = data.unread;
            } else {
              const menuItem = document.querySelector('.menu a.active');
              const badge = document.createElement('span');
              badge.className = 'notification-dot';
              badge.textContent = data.unread;
              menuItem.appendChild(badge);
            }
          } else if (sidebarBadge) {
            sidebarBadge.remove();
          }

          // Tabs badges
          const likesTab = document.querySelector('.tab[data-tab="likes"] .tab-badge');
          if (data.likes > 0) {
            if (likesTab) likesTab.textContent = data.likes;
            else {
              const badge = document.createElement('span');
              badge.className = 'tab-badge';
              badge.textContent = data.likes;
              document.querySelector('.tab[data-tab="likes"]').appendChild(badge);
            }
          } else if (likesTab) likesTab.remove();

          const commentsTab = document.querySelector('.tab[data-tab="comments"] .tab-badge');
          if (data.comments > 0) {
            if (commentsTab) commentsTab.textContent = data.comments;
            else {
              const badge = document.createElement('span');
              badge.className = 'tab-badge';
              badge.textContent = data.comments;
              document.querySelector('.tab[data-tab="comments"]').appendChild(badge);
            }
          } else if (commentsTab) commentsTab.remove();

          const followsTab = document.querySelector('.tab[data-tab="follows"] .tab-badge');
          if (data.follows > 0) {
            if (followsTab) followsTab.textContent = data.follows;
            else {
              const badge = document.createElement('span');
              badge.className = 'tab-badge';
              badge.textContent = data.follows;
              document.querySelector('.tab[data-tab="follows"]').appendChild(badge);
            }
          } else if (followsTab) followsTab.remove();
        });
    }

    function followUser(userId, button) {
      fetch(`/follow/${userId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            button.innerHTML = '<i class="fa-solid fa-check"></i> Following';
            button.classList.remove('btn-follow');
            button.classList.add('btn-reply');
            button.onclick = null;
          }
        });
    }

    function viewVideo(videoId) {
      if (videoId) {
        window.location.href = `/video/${videoId}`;
      }
    }

    function goToUserProfile(userIdentifier) {
      if (userIdentifier && isNaN(userIdentifier)) {
        window.location.href = `/user/${userIdentifier}`;
      } else {
        window.location.href = `/profile/${userIdentifier}`;
      }
    }

    // Real-time updates (optional)
    function setupRealTimeUpdates() {
      setInterval(() => {
        // Fetch new notifications
        const tabType = document.querySelector('.tab.active').dataset.tab || 'all';
        fetch(`/notifications/fetch-latest?tab=${tabType}`)
          .then(response => response.json())
          .then(data => {
            renderNotifications(data.notifications);
            updateUnreadCounts(); // update badges
          });
      }, 15000); // every 15 seconds
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
      setupRealTimeUpdates();
    });

    function renderNotifications(notifications) {
  const container = document.querySelector('.notifications-list');
  container.innerHTML = '';

  if (notifications.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <i class="fa-regular fa-bell"></i>
        <h3>No Notifications Yet</h3>
        <p>When you get likes, comments, or new followers, they'll appear here.</p>
      </div>`;
    return;
  }

  notifications.forEach(notification => {
    const isUnread = !notification.read ? 'unread' : '';
    const videoThumbnail = notification.video?.thumbnail_url || notification.video?.url || '';
    const videoCaption = notification.video?.caption || 'Check out this video';

    const notificationHTML = `
      <div class="notification-item ${isUnread}" 
           data-notification-id="${notification.id}"
           onclick="markAsRead(${notification.id}, this)">
        <div class="notification-icon icon-${notification.type}">
          ${
            notification.type === 'like' ? '<i class="fa-solid fa-heart"></i>' :
            notification.type === 'comment' ? '<i class="fa-solid fa-comment"></i>' :
            notification.type === 'follow' ? '<i class="fa-solid fa-user-plus"></i>' :
            notification.type === 'share' ? '<i class="fa-solid fa-share"></i>' :
            '<i class="fa-solid fa-bell"></i>'
          }
        </div>

        <img src="${notification.from_user.avatar ? notification.from_user.avatar : '/image/default-avatar.png'}"
             alt="${notification.from_user.name}" class="notification-avatar"
             onclick="event.stopPropagation(); goToUserProfile('${notification.from_user.username || notification.from_user.id}');">

        <div class="notification-content">
          <div class="notification-text">
            <span class="notification-user" onclick="event.stopPropagation(); goToUserProfile('${notification.from_user.username || notification.from_user.id}');">
              ${notification.from_user.username || notification.from_user.name}
            </span> 
            ${notification.message}
          </div>
          <div class="notification-time">${moment(notification.created_at).fromNow()}</div>
          ${notification.video ? `<div class="notification-preview">${videoCaption}</div>` : ''}
          <div class="notification-actions">
            ${
              notification.type === 'follow' ? `<button class="btn-small btn-follow" onclick="event.stopPropagation(); followUser(${notification.from_user.id}, this);">
                <i class="fa-solid fa-user-plus"></i> Follow back
              </button>` :
              (notification.type === 'comment' && notification.video ? `<button class="btn-small btn-reply" onclick="event.stopPropagation(); viewVideo(${notification.video.id});">
                <i class="fa-solid fa-eye"></i> View video
              </button>` :
              (notification.type === 'like' && notification.video ? `<button class="btn-small btn-reply" onclick="event.stopPropagation(); viewVideo(${notification.video.id});">
                <i class="fa-solid fa-eye"></i> View video
              </button>` : '')
            )
            }
          </div>
        </div>

        ${videoThumbnail ? `<img src="${videoThumbnail}" class="notification-media" onclick="event.stopPropagation(); viewVideo(${notification.video.id});">` : ''}
      </div>`;
    
    container.insertAdjacentHTML('beforeend', notificationHTML);
  });
}
  </script>
</body>
</html>