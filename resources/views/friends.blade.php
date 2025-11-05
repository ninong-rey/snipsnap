<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SnipSnap - Friends</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --accent: #ff0050;
      --text-color: #000;
      --muted: #666;
      --light-bg: #f8f8f8;
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

    /* ==== CONTENT SKELETON STATES ==== */
    .friends-container.skeleton-loading .friends-title,
    .friends-container.skeleton-loading .friends-tabs,
    .friends-container.skeleton-loading .friend-item,
    .friends-container.skeleton-loading .section-title,
    .friends-container.skeleton-loading .active-friend,
    .friends-container.skeleton-loading .empty-state > * {
      position: relative;
    }

    .friends-container.skeleton-loading .friends-title::before,
    .friends-container.skeleton-loading .section-title::before,
    .friends-container.skeleton-loading .empty-state h3::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Hide actual content during skeleton loading */
    .friends-container.skeleton-loading .friends-title,
    .friends-container.skeleton-loading .tab,
    .friends-container.skeleton-loading .friend-avatar,
    .friends-container.skeleton-loading .friend-name,
    .friends-container.skeleton-loading .friend-username,
    .friends-container.skeleton-loading .friend-bio,
    .friends-container.skeleton-loading .friend-stats,
    .friends-container.skeleton-loading .btn,
    .friends-container.skeleton-loading .active-avatar,
    .friends-container.skeleton-loading .active-name,
    .friends-container.skeleton-loading .empty-state i,
    .friends-container.skeleton-loading .empty-state h3,
    .friends-container.skeleton-loading .empty-state p,
    .friends-container.skeleton-loading .find-friends-btn {
      opacity: 0;
    }

    /* Skeleton for tabs */
    .friends-container.skeleton-loading .friends-tabs::before {
      content: '';
      display: flex;
      gap: 20px;
    }

    .friends-container.skeleton-loading .friends-tabs::before .skeleton-tab {
      width: 100px;
      height: 40px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for friend items */
    .friends-container.skeleton-loading .friend-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
    }

    .friends-container.skeleton-loading .friend-item::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 16px;
      transform: translateY(-50%);
      width: 56px;
      height: 56px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .friends-container.skeleton-loading .friend-info::before {
      content: '';
      position: absolute;
      left: 84px;
      top: 20px;
      width: 120px;
      height: 16px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .friends-container.skeleton-loading .friend-info::after {
      content: '';
      position: absolute;
      left: 84px;
      top: 45px;
      width: 80px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .friends-container.skeleton-loading .friend-actions::before {
      content: '';
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      width: 80px;
      height: 32px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 8px;
    }

    /* Skeleton for active friends */
    .friends-container.skeleton-loading .active-friend::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .friends-container.skeleton-loading .active-friend::after {
      content: '';
      width: 64px;
      height: 64px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .friends-container.skeleton-loading .active-friend .active-name::before {
      content: '';
      width: 50px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Skeleton for empty state */
    .friends-container.skeleton-loading .empty-state::before {
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

    .friends-container.skeleton-loading .empty-state::after {
      content: '';
      width: 64px;
      height: 64px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
      margin-bottom: 16px;
    }

    .friends-container.skeleton-loading .empty-state h3::before {
      content: '';
      width: 200px;
      height: 18px;
      margin-bottom: 8px;
    }

    .friends-container.skeleton-loading .empty-state p::before {
      content: '';
      width: 300px;
      height: 14px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .friends-container.skeleton-loading .empty-state .find-friends-btn::before {
      content: '';
      width: 120px;
      height: 24px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Sidebar - Same as your other pages */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100vh;
      background: #fff;
      border-right: 1px solid #eee;
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

    /* Friends Content - TikTok Style */
    .friends-container {
      margin-left: 260px;
      width: calc(100% - 260px);
      height: 100vh;
      overflow-y: auto;
      background: #fff;
      transition: opacity 0.3s ease;
    }

    .friends-container.skeleton-loading {
      opacity: 0.9;
    }

    .friends-header {
      padding: 20px 24px 0;
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 10;
      border-bottom: 1px solid #eee;
    }

    .friends-title {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 16px;
    }

    /* TikTok-style Tabs */
    .friends-tabs {
      display: flex;
      gap: 0;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .friends-tabs::-webkit-scrollbar {
      display: none;
    }

    .tab {
      padding: 12px 16px;
      font-weight: 600;
      color: var(--muted);
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
      white-space: nowrap;
      font-size: 15px;
    }

    .tab.active {
      color: var(--text-color);
      border-bottom: 2px solid var(--text-color);
    }

    /* Friends List - TikTok Style */
    .friends-list {
      padding: 16px;
    }

    .friend-item {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      border-radius: 12px;
      transition: background 0.2s;
      cursor: pointer;
      position: relative;
    }

    .friend-item:hover {
      background: #f8f8f8;
    }

    .avatar-container {
      position: relative;
      margin-right: 12px;
    }

    .friend-avatar {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #f0f0f0;
    }

    .online-dot {
      width: 14px;
      height: 14px;
      background: #00d600;
      border-radius: 50%;
      border: 2px solid #fff;
      position: absolute;
      bottom: 2px;
      right: 2px;
    }

    .friend-info {
      flex: 1;
      position: relative;
    }

    .friend-main {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 4px;
    }

    .friend-name {
      font-weight: 600;
      font-size: 16px;
    }

    .friend-username {
      color: var(--muted);
      font-size: 14px;
    }

    .friend-bio {
      color: var(--muted);
      font-size: 14px;
      line-height: 1.3;
      margin-bottom: 6px;
    }

    .friend-stats {
      display: flex;
      gap: 12px;
      font-size: 13px;
      color: var(--muted);
    }

    .friend-actions {
      display: flex;
      gap: 8px;
      position: relative;
    }

    .btn {
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-size: 13px;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .btn-primary {
      background: var(--accent);
      color: #fff;
    }

    .btn-primary:hover {
      background: #e00040;
    }

    .btn-secondary {
      background: var(--light-bg);
      color: var(--text-color);
    }

    .btn-secondary:hover {
      background: #e8e8e8;
    }

    /* TikTok-style Active Now Section */
    .active-now {
      padding: 16px;
      border-bottom: 1px solid #eee;
      position: relative;
    }

    .section-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 12px;
      color: var(--text-color);
    }

    .active-friends {
      display: flex;
      gap: 16px;
      overflow-x: auto;
      padding-bottom: 8px;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .active-friends::-webkit-scrollbar {
      display: none;
    }

    .active-friend {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      position: relative;
    }

    .active-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--accent);
      position: relative;
    }

    .active-name {
      font-size: 12px;
      font-weight: 500;
      max-width: 64px;
      text-align: center;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    /* Empty State - TikTok Style */
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

    /* Smaller Find Friends Button */
    .find-friends-btn {
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 4px;
      padding: 4px 8px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.2s;
    }

    .find-friends-btn i {
      font-size: 20px;
    }

    .find-friends-btn:hover {
      background: #e00040;
      transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 900px) {
      .sidebar {
        display: none;
      }
      .friends-container {
        margin-left: 0;
        width: 100%;
      }
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
        <input type="text" placeholder="Search friends...">
      </div>

      <div class="menu">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i>For You</a>
        <a href="{{ route('explore.users') }}"><i class="fa-regular fa-compass"></i>Explore</a>
        <a href="{{ route('following.videos') }}"><i class="fa-solid fa-user-group"></i>Following</a>
        <a href="{{ route('friends') }}" class="active"><i class="fa-solid fa-user-friends"></i>Friends</a>
        <a href="{{ route('upload') }}"><i class="fa-solid fa-plus-square"></i>Upload</a>
        <a href="{{ route('notifications') }}"><i class="fa-regular fa-comment-dots"></i>Notifications</a>
        <a href="messages.index"><i class="fa-regular fa-paper-plane"></i>Messages</a>
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

  <!-- Friends Content -->
  <div class="friends-container" id="friendsContainer">
    @if($friends->isEmpty())
      <!-- Empty State when no friends -->
      <div class="empty-state">
        <i class="fa-solid fa-user-friends"></i>
        <h3>No Friends Yet</h3>
        <p>When you follow people and they follow you back, they'll appear here as friends.</p>
        <a href="{{ route('explore.users') }}" class="find-friends-btn">
          <i class="fa-solid fa-user-plus"></i> Find Friends
        </a>
      </div>
    @else
      <!-- Header and Content when friends exist -->
      <div class="friends-header">
        <h1 class="friends-title">Friends</h1>
        
        <!-- TikTok-style Tabs -->
        <div class="friends-tabs">
          <div class="tab active" data-tab="all">All Friends</div>
          <div class="tab" data-tab="online">Online</div>
          <div class="tab" data-tab="suggestions">Suggestions</div>
          <div class="tab" data-tab="requests">Requests</div>
        </div>
      </div>

      <!-- Active Now Section (TikTok-style) -->
      @if($activeFriends && $activeFriends->count() > 0)
      <div class="active-now">
        <div class="section-title">Active Now</div>
        <div class="active-friends">
          @foreach($activeFriends as $friend)
          <div class="active-friend" onclick="goToUserProfile('{{ $friend->username ?? $friend->id }}')">
            <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('default-avatar.png') }}" 
                 alt="{{ $friend->name }}" class="active-avatar">
            <div class="active-name">{{ $friend->username ?? $friend->name }}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Friends List -->
      <div class="friends-list">
        @foreach($friends as $friend)
        <div class="friend-item">
          <div class="avatar-container">
            <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('default-avatar.png') }}" 
                 alt="{{ $friend->name }}" class="friend-avatar">
            <!-- Online dot would go here if you implement online status -->
            <!-- <div class="online-dot"></div> -->
          </div>
          <div class="friend-info">
            <div class="friend-main">
              <div class="friend-name">{{ $friend->name }}</div>
              <div class="friend-username">@{{ $friend->username ?? $friend->name }}</div>
            </div>
            <div class="friend-bio">{{ $friend->bio ?? 'No bio yet' }}</div>
            <div class="friend-stats">
              <span>{{ $friend->videos_count ?? 0 }} videos</span>
              <span>•</span>
              <span>{{ $friend->followers_count ?? 0 }} followers</span>
            </div>
          </div>
          <div class="friend-actions">
            <button class="btn btn-primary">
              <i class="fa-solid fa-comment"></i> Message
            </button>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Friend Suggestions Section -->
      @if($suggestions && $suggestions->count() > 0)
      <div class="active-now">
        <div class="section-title">Friend Suggestions</div>
        <div class="active-friends">
          @foreach($suggestions as $suggestion)
          <div class="active-friend" onclick="goToUserProfile('{{ $suggestion->username ?? $suggestion->id }}')">
            <img src="{{ $suggestion->avatar ? asset('storage/' . $suggestion->avatar) : asset('default-avatar.png') }}" 
                 alt="{{ $suggestion->name }}" class="active-avatar">
            <div class="active-name">{{ $suggestion->username ?? $suggestion->name }}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    @endif
  </div>

  <script>
    // ===== SKELETON LOADER FUNCTIONS =====
    let loaderTimeout;

    // Show skeleton loading
    function showSkeleton() {
      const friendsContainer = document.getElementById('friendsContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (friendsContainer) {
        friendsContainer.classList.add('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.add('skeleton-loading');
      }
    }

    // Hide skeleton loading
    function hideSkeleton() {
      const friendsContainer = document.getElementById('friendsContainer');
      const sidebar = document.getElementById('sidebar');
      
      if (friendsContainer) {
        friendsContainer.classList.remove('skeleton-loading');
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

    // Function to navigate to user profile
    function goToUserProfile(userIdentifier) {
      if (userIdentifier && isNaN(userIdentifier)) {
        window.location.href = `/user/${userIdentifier}`;
      } else {
        window.location.href = `/profile`;
      }
    }

    // Tab functionality
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const tabType = this.dataset.tab;
        console.log('Switched to tab:', tabType);
        // Implement filtering logic here
      });
    });

    // Friend item click to go to profile
    document.querySelectorAll('.friend-item').forEach(item => {
      item.addEventListener('click', function(e) {
        if (!e.target.closest('.friend-actions')) {
          const username = this.querySelector('.friend-username').textContent.replace('@', '');
          goToUserProfile(username);
        }
      });
    });

    // Message button functionality
    document.querySelectorAll('.btn-primary').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const friendItem = this.closest('.friend-item');
        const username = friendItem.querySelector('.friend-username').textContent.replace('@', '');
        // Implement message functionality here
        console.log('Message friend:', username);
        alert(`Messaging feature coming soon for @${username}`);
      });
    });
  </script>
</body>
</html>