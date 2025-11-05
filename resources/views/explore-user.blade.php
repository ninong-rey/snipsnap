<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explore Creators - SnipSnap+</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    /* === Keep all your existing CSS === */
    :root { --accent: #ff0050; --text-color: #000; --muted: #666; --bg: #fff; --hover-bg: #f9f9f9; }
    body { margin:0; font-family:"Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background:var(--bg); color:var(--text-color); display:flex; }
    /* ... (rest of your CSS stays identical) ... */
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
        <input type="text" placeholder="Search creators...">
      </div>

      <div class="menu">
        <a href="{{ route('home') }}" data-route="for-you">
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
      @foreach($trendingUsers ?? [] as $trend)
        <div class="trending-item">
          <div class="trending-avatar">{{ strtoupper(substr($trend->username ?? 'U', 0, 1)) }}</div>
          <div class="trending-name">{{ $trend->username ?? 'Unknown' }}</div>
          <div class="trending-tag">{{ $trend->followers_count ?? 0 }} followers</div>
        </div>
      @endforeach
    </div>

    <h2 style="margin-top:40px;">Discover More Creators</h2>

    <div class="users-grid">
      @foreach($users ?? [] as $user)
        <div class="user-card">
          <div class="user-avatar">{{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}</div>
          <h3>{{ $user->username ?? 'Unknown' }}</h3>
          <p style="color:var(--muted)">{{ $user->followers_count ?? 0 }} followers</p>
          <button class="follow-btn" data-user-id="{{ $user->id ?? 0 }}">Follow</button>
        </div>
      @endforeach
    </div>
  </main>

  <script>
    // ===== Skeleton Loader & Sidebar JS =====
    let loaderTimeout;

    function showContentSkeleton() {
      const mainContent = document.getElementById('mainContent');
      const sidebar = document.getElementById('sidebar');
      if (mainContent) mainContent.classList.add('skeleton-loading');
      if (sidebar) sidebar.classList.add('skeleton-loading');
    }

    function hideContentSkeleton() {
      const mainContent = document.getElementById('mainContent');
      const sidebar = document.getElementById('sidebar');
      if (mainContent) mainContent.classList.remove('skeleton-loading');
      if (sidebar) sidebar.classList.remove('skeleton-loading');
    }

    function showSidebarSkeleton(link) { if (link) link.classList.add('loading'); }
    function hideSidebarSkeleton() { document.querySelectorAll('.menu a.loading').forEach(link => link.classList.remove('loading')); }
    function showActivePageSkeleton() { showSidebarSkeleton(document.querySelector('.menu a.active')); }

    function initSkeletonLoader() {
      showContentSkeleton();
      showActivePageSkeleton();
      setTimeout(() => { hideContentSkeleton(); hideSidebarSkeleton(); }, 2000);
    }

    function initSidebarNavigation() {
      document.querySelectorAll('.menu a').forEach(link => {
        link.addEventListener('click', function(e) {
          if (this.classList.contains('active') || this.getAttribute('href') === '#') return;
          showSidebarSkeleton(this);
          showContentSkeleton();
          loaderTimeout = setTimeout(() => { hideContentSkeleton(); hideSidebarSkeleton(); }, 3000);
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      initSkeletonLoader();
      initSidebarNavigation();
    });

    // Follow button functionality
    document.querySelectorAll('.follow-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.userId;
        if (!id) return; // Safety
        const isFollowing = this.classList.contains('following');
        const action = isFollowing ? 'unfollow' : 'follow';

        fetch(`/profile/${id}/${action}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.classList.toggle('following');
            this.textContent = isFollowing ? 'Follow' : 'Following';
          }
        }).catch(err => console.error(err));
      });
    });
  </script>
</body>
</html>
