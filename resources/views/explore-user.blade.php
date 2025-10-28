<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explore Creators - SnipSnap+</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --accent: #ff0050;
      --text-color: #000;
      --muted: #666;
      --bg: #fff;
      --hover-bg: #f9f9f9;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: var(--text-color);
      display: flex;
    }

    /* ==== SKELETON LOADER STYLES ==== */
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

    /* Page content skeleton states */
    .main-content.skeleton-loading .page-header h1,
    .main-content.skeleton-loading .page-header p,
    .main-content.skeleton-loading .trending-avatar,
    .main-content.skeleton-loading .trending-name,
    .main-content.skeleton-loading .trending-tag,
    .main-content.skeleton-loading .user-avatar,
    .main-content.skeleton-loading .user-card h3,
    .main-content.skeleton-loading .user-card p,
    .main-content.skeleton-loading .follow-btn {
      visibility: hidden;
      position: relative;
    }

    .main-content.skeleton-loading .page-header h1::after,
    .main-content.skeleton-loading .page-header p::after,
    .main-content.skeleton-loading .trending-name::after,
    .main-content.skeleton-loading .trending-tag::after,
    .main-content.skeleton-loading .user-card h3::after,
    .main-content.skeleton-loading .user-card p::after {
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

    /* Skeleton replacements for specific elements */
    .main-content.skeleton-loading .page-header {
      position: relative;
    }

    .main-content.skeleton-loading .page-header::before {
      content: '';
      display: block;
      height: 32px;
      width: 200px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 6px;
      margin-bottom: 8px;
    }

    .main-content.skeleton-loading .page-header::after {
      content: '';
      display: block;
      height: 16px;
      width: 300px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    .main-content.skeleton-loading .trending-avatar::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
    }

    .main-content.skeleton-loading .user-avatar::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
    }

    .main-content.skeleton-loading .follow-btn::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 80px;
      height: 32px;
      border-radius: 20px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
    }

    /* ==== SIDEBAR SKELETON LOADER ==== */
    .sidebar.skeleton-loading {
      background: #fff;
    }

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

    /* ==== SIDEBAR SKELETON STATES ==== */
    .menu a.loading {
      position: relative;
      overflow: hidden;
      pointer-events: none;
    }

    .menu a.loading .menu-text,
    .menu a.loading i {
      opacity: 0;
    }

    .menu a.loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      width: calc(100% - 24px);
      height: 20px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    /* Sidebar */
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

    /* Main Explore content */
    .main-content {
      margin-left: 260px;
      flex: 1;
      padding: 40px;
      box-sizing: border-box;
      min-height: 100vh;
      transition: opacity 0.3s ease;
    }

    .main-content.skeleton-loading {
      opacity: 0.7;
    }

    .page-header h1 {
      font-size: 28px;
      margin: 0;
      color: #000;
    }

    .page-header p {
      color: var(--muted);
    }

    /* Trending Carousel */
    .trending-carousel {
      display: flex;
      overflow-x: auto;
      gap: 20px;
      padding-bottom: 10px;
      scroll-snap-type: x mandatory;
    }

    .trending-item {
      flex: 0 0 auto;
      width: 160px;
      text-align: center;
      scroll-snap-align: start;
      transition: transform 0.3s ease;
    }

    .trending-item:hover {
      transform: scale(1.08);
    }

    .trending-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin: 0 auto 8px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 28px;
      position: relative;
    }

    .trending-name {
      font-size: 15px;
      font-weight: 600;
      position: relative;
    }

    .trending-tag {
      color: var(--accent);
      font-size: 13px;
      position: relative;
    }

    /* User Grid */
    .users-grid {
      margin-top: 40px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .user-card {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: 0.3s;
    }

    .user-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .user-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ff8a00, #e52e71);
      margin: 0 auto 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      font-weight: bold;
      position: relative;
    }

    .follow-btn {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 8px 22px;
      border-radius: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
      position: relative;
    }

    .follow-btn:hover {
      background: #e00045;
      transform: scale(1.05);
    }

    .follow-btn.following {
      background: #fff;
      color: var(--accent);
      border: 1px solid var(--accent);
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
        <input type="text" placeholder="Search creators...">
      </div>

      <div class="menu">
        <a href="{{ route('my-web') }}" data-route="for-you">
          <i class="fa-solid fa-house"></i>
          <span class="menu-text">For You</span>
        </a>
        <a href="{{ route('explore.users') }}" class="active" data-route="explore">
          <i class="fa-regular fa-compass"></i>
          <span class="menu-text">Explore</span>
        </a>
        <a href="{{ route('following.videos') }}" data-route="following">
          <i class="fa-solid fa-user-group"></i>
          <span class="menu-text">Following</span>
        </a>
        <a href="{{ route('friends') }}" data-route="friends">
          <i class="fa-solid fa-user-friends"></i>
          <span class="menu-text">Friends</span>
        </a>
        <a href="{{ route('upload') }}" data-route="upload">
          <i class="fa-solid fa-plus-square"></i>
          <span class="menu-text">Upload</span>
        </a>
        <a href="{{ route('notifications') }}" data-route="notifications">
          <i class="fa-regular fa-comment-dots"></i>
          <span class="menu-text">Notifications</span>
        </a>
        <a href="{{ route('messages.index') }}" data-route="messages">
          <i class="fa-regular fa-paper-plane"></i>
          <span class="menu-text">Messages</span>
        </a>
        <a href="#" data-route="live">
          <i class="fa-solid fa-tv"></i>
          <span class="menu-text">LIVE</span>
        </a>
        <a href="{{ route('profile.show') }}" data-route="profile">
          <i class="fa-solid fa-user"></i>
          <span class="menu-text">Profile</span>
        </a>
        <a href="#" data-route="more">
          <i class="fa-solid fa-ellipsis"></i>
          <span class="menu-text">More</span>
        </a>
      </div>
    </div>

    <form method="POST" action="{{ route('logout.perform') }}">
      @csrf
      <button style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:14px;">Logout</button>
    </form>
  </aside>

  <!-- Main Explore Section -->
  <main class="main-content" id="mainContent">
    <div class="page-header">
      <h1>Trending Creators 🔥</h1>
      <p>Discover the top SnipSnap stars right now</p>
    </div>

    <div class="trending-carousel">
      @foreach($trendingUsers as $trend)
        <div class="trending-item">
          <div class="trending-avatar">{{ strtoupper(substr($trend->username, 0, 1)) }}</div>
          <div class="trending-name">{{ $trend->username }}</div>
          <div class="trending-tag">{{ $trend->followers_count }} followers</div>
        </div>
      @endforeach
    </div>

    <h2 style="margin-top:40px;">Discover More Creators</h2>

    <div class="users-grid">
      @foreach($users as $user)
      <div class="user-card">
        <div class="user-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
        <h3>{{ $user->username }}</h3>
        <p style="color:var(--muted)">{{ $user->followers_count }} followers</p>
        <button class="follow-btn" data-user-id="{{ $user->id }}">Follow</button>
      </div>
      @endforeach
    </div>
  </main>

  <script>
    // ===== SKELETON LOADER FUNCTIONS =====
    let loaderTimeout;

    // Show content skeleton loading
    function showContentSkeleton() {
      const mainContent = document.getElementById('mainContent');
      const sidebar = document.getElementById('sidebar');
      if (mainContent) {
        mainContent.classList.add('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.add('skeleton-loading');
      }
    }

    // Hide content skeleton loading
    function hideContentSkeleton() {
      const mainContent = document.getElementById('mainContent');
      const sidebar = document.getElementById('sidebar');
      if (mainContent) {
        mainContent.classList.remove('skeleton-loading');
      }
      if (sidebar) {
        sidebar.classList.remove('skeleton-loading');
      }
    }

    // Show individual sidebar link skeleton
    function showSidebarSkeleton(link) {
      if (link) {
        link.classList.add('loading');
      }
    }

    // Hide sidebar skeleton
    function hideSidebarSkeleton() {
      document.querySelectorAll('.menu a.loading').forEach(link => {
        link.classList.remove('loading');
      });
    }

    // Show active page skeleton on reload
    function showActivePageSkeleton() {
      const activeLink = document.querySelector('.menu a.active');
      if (activeLink) {
        showSidebarSkeleton(activeLink);
      }
    }

    // Initialize skeleton loader on page load
    function initSkeletonLoader() {
      // Show skeleton immediately
      showContentSkeleton();
      showActivePageSkeleton();
      
      // Hide loader after content is loaded (simulate loading time)
      setTimeout(() => {
        hideContentSkeleton();
        hideSidebarSkeleton();
      }, 2000); // 2 seconds loading time
    }

    // Enhanced navigation with skeleton loaders
    function initSidebarNavigation() {
      const sidebarLinks = document.querySelectorAll('.menu a');
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          if (this.classList.contains('active') || this.getAttribute('href') === '#') {
            return;
          }
          
          // Show skeleton for clicked link and content
          showSidebarSkeleton(this);
          showContentSkeleton();
          
          // Set timeout to hide loader (in case navigation is slow)
          loaderTimeout = setTimeout(() => {
            hideContentSkeleton();
            hideSidebarSkeleton();
          }, 3000);
        });
      });
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
      initSkeletonLoader();
      initSidebarNavigation();
    });

    // Follow button functionality
    document.querySelectorAll('.follow-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.userId;
        const isFollowing = this.classList.contains('following');
        const action = isFollowing ? 'unfollow' : 'follow';

        fetch(`/profile/${id}/${action}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.classList.toggle('following');
            this.textContent = isFollowing ? 'Follow' : 'Following';
          }
        });
      });
    });
  </script>
</body>
</html>