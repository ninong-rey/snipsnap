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

    /* Sidebar (copied from web.blade) */
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
    }

    .trending-name {
      font-size: 15px;
      font-weight: 600;
    }

    .trending-tag {
      color: var(--accent);
      font-size: 13px;
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
  <aside class="sidebar">
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
        <a href="{{ route('my-web') }}"><i class="fa-solid fa-house"></i>For You</a>
        <a href="{{ route('explore.users') }}" class="active"><i class="fa-regular fa-compass"></i>Explore</a>
        <a href="{{ route('following.videos') }}"><i class="fa-solid fa-user-group"></i>Following</a>
        <a href="{{ route('friends') }}"><i class="fa-solid fa-user-friends"></i>Friends</a>
        <a href="{{ route('upload') }}"><i class="fa-solid fa-plus-square"></i>Upload</a>
        <a href="{{ route('notifications') }}"><i class="fa-regular fa-comment-dots"></i>Notifications</a>
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

  <!-- Main Explore Section -->
  <main class="main-content">
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
