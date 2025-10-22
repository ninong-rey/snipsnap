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
    padding: 4px 8px;      /* ← Less padding */
    font-size: 12px;        /* ← Smaller text */
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;               /* ← Less space between icon and text */
    transition: all 0.2s;
}
.find-friends-btn i {
    font-size: 20px;        /* Small icon */
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
  <aside class="sidebar">
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
        <a href="{{ route('my-web') }}"><i class="fa-solid fa-house"></i>For You</a>
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
  <div class="friends-container">
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