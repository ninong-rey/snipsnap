@php
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SnipSnap Web</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --accent: #ff0050;
      --text-color: #000;
      --muted: #666;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
      color: var(--text-color);
      overflow: hidden;
    }

    /* ==== SKELETON LOADER STYLES ==== */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
    }

    @keyframes shimmer {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }

    .sidebar.skeleton-loading .logo::before,
    .sidebar.skeleton-loading .search-box::before,
    .sidebar.skeleton-loading .menu a::before {
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
    .sidebar.skeleton-loading .menu a > * {
      opacity: 0;
    }

    .feed-container.skeleton-loading .video-wrapper::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 16px;
      z-index: 2;
    }

    .feed-container.skeleton-loading .actions::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 48px;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 24px;
    }

    .feed-container.skeleton-loading .user-avatar-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
      z-index: 2;
    }

    .feed-container.skeleton-loading .action-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 26px;
      height: 26px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 50%;
    }

    .feed-container.skeleton-loading .action-count::before {
      content: '';
      display: block;
      width: 20px;
      height: 12px;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 3px;
      margin-top: 4px;
    }

    .feed-container.skeleton-loading .caption::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 70%;
      height: 100%;
      background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
      background-size: 400% 100%;
      animation: shimmer 2s infinite ease-in-out;
      border-radius: 4px;
      z-index: 2;
    }

    .feed-container.skeleton-loading video,
    .feed-container.skeleton-loading .actions,
    .feed-container.skeleton-loading .caption,
    .feed-container.skeleton-loading .video-controls,
    .feed-container.skeleton-loading .play-pause-animation {
      opacity: 0;
    }

    /* ==== SIDEBAR ==== */
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
      z-index: 100;
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

    .logo img { 
      width: 28px; 
      height: 28px; 
      border-radius: 6px;
    }

    .search-box {
      background: #f3f3f3;
      border-radius: 50px;
      padding: 10px 15px;
      display: flex;
      align-items: center;
      margin-bottom: 24px;
      gap: 10px;
      transition: all 0.3s ease;
    }

    .search-box:focus-within {
      background: #e8e8e8;
      box-shadow: 0 0 0 2px rgba(254, 44, 85, 0.1);
    }

    .search-box input {
      border: none;
      background: none;
      width: 100%;
      outline: none;
      font-size: 14px;
      color: var(--text-color);
    }

    .menu { 
      flex-grow: 1; 
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--text-color);
      text-decoration: none;
      padding: 10px 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      border-radius: 8px;
      position: relative;
      overflow: hidden;
    }

    .menu a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(254, 44, 85, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .menu a:hover::before,
    .menu a.active::before {
      left: 100%;
    }

    .menu a:hover,
    .menu a.active {
      color: var(--accent);
      font-weight: bold;
      background: #f7f7f7;
      transform: translateX(4px);
    }

    .menu i {
      font-size: 18px;
      width: 22px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .menu a:hover i,
    .menu a.active i {
      transform: scale(1.1);
    }

    .menu-text {
      transition: opacity 0.3s ease;
    }

    /* ==== FEED ==== */
    .feed-container {
      margin-left: 260px;
      width: calc(100% - 260px);
      height: 100vh;
      overflow-y: scroll;
      scroll-snap-type: y mandatory;
      background: #fff;
    }

    .video-post {
      width: 100%;
      height: 100vh;
      scroll-snap-align: start;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fff;
      position: relative;
    }

    .video-wrapper {
      position: relative;
      width: 100%;
      max-width: 420px;
      aspect-ratio: 9 / 16;
      background: #000;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
      margin-right: 40%;
    }

    video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* ==== VIDEO CONTROLS ==== */
    .video-controls {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 5;
    }

    .video-wrapper:hover .video-controls {
      opacity: 1;
    }

    .control-btn {
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.2s ease;
    }

    .control-btn:hover {
      background: rgba(0, 0, 0, 0.7);
      transform: scale(1.1);
    }

    .volume-slider {
      width: 0;
      opacity: 0;
      transition: all 0.3s ease;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 10px;
      overflow: hidden;
    }

    .volume-container:hover .volume-slider {
      width: 80px;
      opacity: 1;
    }

    .volume-slider input {
      width: 100%;
      height: 4px;
      cursor: pointer;
    }

    /* ==== ANIMATIONS ==== */
    .play-pause-animation {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0);
      color: white;
      font-size: 80px;
      opacity: 0;
      pointer-events: none;
      z-index: 10;
      transition: all 0.3s ease;
    }

    .play-pause-animation.active {
      animation: playPulse 0.6s ease-out forwards;
    }

    @keyframes playPulse {
      0% { transform: translate(-50%, -50%) scale(0); opacity: 0; }
      50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
      100% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
    }

    .heart {
      position: absolute;
      color: var(--accent);
      font-size: 90px;
      opacity: 0;
      pointer-events: none;
      transform: scale(0);
      transition: opacity 0.3s, transform 0.3s;
      z-index: 10;
    }

    .heart.active {
      animation: floatHeart 1.4s ease-out forwards;
    }

    @keyframes floatHeart {
      0% { transform: scale(1) translateY(0) rotate(0deg); opacity: 1; }
      40% { transform: scale(1.2) translateY(-40px) rotate(10deg); opacity: 0.9; }
      100% { transform: scale(0.8) translateY(-150px) rotate(-10deg); opacity: 0; }
    }

    /* ==== ACTIONS ==== */
    .actions {
      position: absolute;
      right: 25px;
      bottom: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 18px;
    }

    .action-btn {
      color: #fff;
      font-size: 26px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      position: relative;
    }

    .action-btn:hover { 
      transform: scale(1.2); 
    }

    .liked { 
      color: var(--accent); 
      animation: heartBeat 0.6s ease;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      50% { transform: scale(1.3); }
      100% { transform: scale(1); }
    }

    .action-count {
      font-size: 12px;
      font-weight: 600;
      color: #fff;
    }

    .user-avatar-btn {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      border: 2px solid #fff;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #fff;
      position: relative;
    }

    .user-avatar-btn:hover {
      transform: scale(1.1);
      border-color: var(--accent);
    }

    .user-avatar-btn img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .caption {
      position: absolute;
      bottom: 20px;
      left: 20px;
      color: #fff;
      font-size: 15px;
      font-weight: 500;
      width: 70%;
      line-height: 1.4;
      position: relative;
    }

    .overlay { 
      position: absolute; 
      inset: 0; 
      cursor: pointer; 
    }

    /* ==== COMMENTS PANEL ==== */
    .comments-panel {
      position: fixed;
      right: -400px;
      top: 0;
      width: 400px;
      height: 100vh;
      background: #fff;
      border-left: 1px solid #ddd;
      box-shadow: -4px 0 12px rgba(0,0,0,0.1);
      transition: right 0.4s ease;
      display: flex;
      flex-direction: column;
      z-index: 20;
    }

    .comments-panel.active { 
      right: 0; 
    }

    .comments-header {
      padding: 16px;
      border-bottom: 1px solid #eee;
      font-weight: bold;
      font-size: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .comments-list {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
    }

    .comment {
      padding: 8px 0;
      border-bottom: 1px solid #f2f2f2;
    }

    .comment-input {
      border-top: 1px solid #eee;
      padding: 10px 16px;
      display: flex;
      gap: 8px;
    }

    .comment-input input {
      flex: 1;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 8px 12px;
      outline: none;
    }

    .comment-input button {
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      cursor: pointer;
      font-size: 16px;
    }

    /* ==== UPLOAD OVERLAY ==== */
    #uploadOverlay {
      position: fixed;
      top: 20px;
      right: 20px;
      width: 160px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      z-index: 9999;
      background: #000;
      animation: slideInRight 0.5s ease;
    }

    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    #uploadOverlay video {
      width: 100%;
      display: block;
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div>
      <div class="logo">
        <img src="{{ secure_asset('image/snipsnap.png') }}" alt="SnipSnap" onerror="this.style.display='none'">
        SnipSnap
      </div>

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search">
      </div>

      <div class="menu">
        <a href="{{ route('my-web') }}" class="active" data-route="for-you">
          <i class="fa-solid fa-house"></i>
          <span class="menu-text">For You</span>
        </a>
        <a href="{{ route('explore.users') }}" data-route="explore">
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
      <button style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:14px;padding:10px 12px;border-radius:8px;width:100%;text-align:left;">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </aside>

  @if(request()->has('uploaded_video'))
  <div id="uploadOverlay">
    <video id="processingVideo" src="{{ request()->get('uploaded_video') }}" autoplay muted loop></video>
    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
      <i class="fas fa-spinner fa-spin" style="color:#fff; font-size:36px;"></i>
    </div>
  </div>
  <script>
    setTimeout(() => {
      const overlay = document.getElementById('uploadOverlay');
      if (overlay) {
        overlay.style.animation = 'slideInRight 0.5s ease reverse';
        setTimeout(() => overlay.remove(), 500);
      }
    }, 3000);
  </script>
  @endif

  <!-- Feed -->
  <main class="feed-container" id="feedContainer">
    @foreach($videos as $video)
    @php $videoUser = $video->user; @endphp
    <div class="video-post" data-video-id="{{ $video->id }}">
      <div class="video-wrapper">
        <!-- Cloudinary Video Player -->
        @if(!empty($video->url))
          <video 
            src="{{ $video->url }}" 
            loop 
            playsinline 
            preload="metadata"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
          </video>
          
          <!-- Fallback if video fails to load -->
          <div style="display:none; width:100%; height:100%; background:#000; align-items:center; justify-content:center; color:#fff; flex-direction:column;">
            <i class="fas fa-video-slash" style="font-size:48px; margin-bottom:10px;"></i>
            <span>Video unavailable</span>
          </div>
        @else
          <!-- Show MixKit fallback video -->
          <video 
            src="https://assets.mixkit.co/videos/preview/mixkit-tree-with-yellow-flowers-1173-large.mp4" 
            loop 
            playsinline 
            preload="metadata">
          </video>
        @endif

        <!-- Play/Pause animation -->
        <div class="play-pause-animation">
          <i class="fas fa-pause"></i>
        </div>

        <!-- Overlay for tap actions -->
        <div class="overlay" onclick="togglePlayPause(this)" ondblclick="doubleTapLike(this, event)"></div>

        <!-- Video controls -->
        <div class="video-controls">
          <div class="volume-container">
            <button class="control-btn volume-btn" onclick="toggleMute(this)">
              <i class="fas fa-volume-up"></i>
            </button>
            <div class="volume-slider">
              <input type="range" min="0" max="1" step="0.1" value="1" oninput="changeVolume(this)">
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="actions">
        @if($videoUser)
        <div class="user-avatar-btn" onclick="goToUserProfile('{{ $videoUser->username ?? $videoUser->id }}')">
          <img src="{{ $videoUser->avatar ? asset('storage/' . $videoUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($videoUser->name ?? 'User') . '&background=fe2c55&color=fff&size=32' }}" 
               alt="{{ $videoUser->username ?? $videoUser->name }}"
               onerror="this.src='https://ui-avatars.com/api/?name=User&background=fe2c55&color=fff&size=32'">
        </div>
        @endif
        <div class="action-btn like-btn" onclick="toggleLike(this, {{ $video->id }})">
          <i class="fa-solid fa-heart"></i>
          <span class="action-count like-count-{{ $video->id }}">{{ $video->likes_count ?? 0 }}</span>
        </div>
        <div class="action-btn" onclick="toggleComments(this)">
          <i class="fa-solid fa-comment"></i>
          <span class="action-count comment-count-{{ $video->id }}">{{ $video->comments_count ?? 0 }}</span>
        </div>
        <div class="action-btn" onclick="shareVideo({{ $video->id }})">
          <i class="fa-solid fa-share"></i>
          <span class="action-count share-count-{{ $video->id }}">{{ $video->shares_count ?? 0 }}</span>
        </div>
      </div>

      <div class="caption">
        <strong>{{ $videoUser ? '@' . ($videoUser->username ?? $videoUser->name) : '@deleted_user' }}</strong><br>
        {{ $video->caption ?? '' }}
      </div>

      <!-- Comments Panel -->
      <div class="comments-panel">
        <div class="comments-header">
          Comments
          <i class="fa-solid fa-xmark" style="cursor:pointer;" onclick="toggleComments(this)"></i>
        </div>
        <div class="comments-list" id="comments-list-{{ $video->id }}">
          @foreach($video->comments->where('parent_id', null) as $comment)
            @php $commentUser = $comment->user; @endphp
            <div class="comment">
              <strong>{{ $commentUser ? '@' . ($commentUser->username ?? $commentUser->name) : '@deleted_user' }}</strong>
              {{ $comment->content ?? '' }}
            </div>
          @endforeach
        </div>
        <div class="comment-input">
          <input type="text" placeholder="Add a comment..." data-video-id="{{ $video->id }}">
          <button onclick="postComment(this)"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
      </div>
    </div>
    @endforeach
  </main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // ===== SKELETON LOADER =====
  function showSkeleton() {
    document.getElementById('feedContainer').classList.add('skeleton-loading');
    document.getElementById('sidebar')?.classList.add('skeleton-loading');
  }

  function hideSkeleton() {
    document.getElementById('feedContainer').classList.remove('skeleton-loading');
    document.getElementById('sidebar')?.classList.remove('skeleton-loading');
  }

  // ===== VIDEO FUNCTIONS =====
  const likedVideos = new Set();
  let lastTapTime = 0;

  function goToUserProfile(userIdentifier) {
    showSkeleton();
    setTimeout(() => {
      window.location.href = (userIdentifier && isNaN(userIdentifier)) ? `/user/${userIdentifier}` : `/profile`;
    }, 500);
  }

  function togglePlayPause(overlay, event) {
    const currentTime = new Date().getTime();
    if (currentTime - lastTapTime < 300) {
      doubleTapLike(overlay, event);
      lastTapTime = 0;
      return;
    }
    lastTapTime = currentTime;

    const video = overlay.closest('.video-wrapper').querySelector('video');
    const icon = overlay.closest('.video-wrapper').querySelector('.play-pause-animation i');

    if (!video) return;

    const animation = overlay.closest('.video-wrapper').querySelector('.play-pause-animation');
    animation.classList.remove('active'); 
    void animation.offsetWidth; 
    animation.classList.add('active');

    if (video.paused) {
      video.play().catch(() => { video.muted = true; video.play().catch(console.error); });
      icon?.classList.replace('fa-play', 'fa-pause');
    } else {
      video.pause();
      icon?.classList.replace('fa-pause', 'fa-play');
    }
  }

  function toggleMute(btn) {
    const video = btn.closest('.video-wrapper').querySelector('video');
    const icon = btn.querySelector('i');
    if (!video) return;
    video.muted = !video.muted;
    icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
  }

  function changeVolume(slider) {
    const video = slider.closest('.video-wrapper').querySelector('video');
    const icon = slider.closest('.volume-container').querySelector('i');
    if (!video) return;
    video.volume = slider.value;
    video.muted = slider.value == 0;
    icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
  }

  function createHeart(x, y, container) {
    const heart = document.createElement('i');
    heart.className = 'fa-solid fa-heart heart active';
    heart.style.left = `${x}px`;
    heart.style.top = `${y}px`;
    container.appendChild(heart);
    setTimeout(() => heart.remove(), 1400);
  }

  function doubleTapLike(overlay, event) {
    const videoWrapper = overlay.closest('.video-wrapper');
    const videoId = overlay.closest('.video-post').dataset.videoId;
    const likeBtn = overlay.closest('.video-post').querySelector('.like-btn i');
    const rect = overlay.getBoundingClientRect();
    createHeart(event.clientX - rect.left, event.clientY - rect.top, videoWrapper);
    if (!likedVideos.has(videoId)) { 
      likeBtn.classList.add('liked'); 
      incrementLike(videoId); 
      likedVideos.add(videoId); 
    }
  }

  function toggleLike(btn, videoId) {
    const videoWrapper = btn.closest('.video-post').querySelector('.video-wrapper');
    const likeIcon = btn.querySelector('i');
    if (!likedVideos.has(videoId)) { 
      likeIcon.classList.add('liked'); 
      createHeart(videoWrapper.offsetWidth/2, videoWrapper.offsetHeight/2, videoWrapper); 
      incrementLike(videoId); 
      likedVideos.add(videoId); 
    }
  }

  function incrementLike(videoId) {
    const countEl = document.querySelector(`.like-count-${videoId}`);
    if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
    fetch(`/video/${videoId}/like`, { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'} }).catch(console.log);
  }

  function shareVideo(videoId) {
    const countEl = document.querySelector(`.share-count-${videoId}`);
    if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
    fetch(`/video/${videoId}/share`, { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'} });
    navigator.clipboard.writeText(`${window.location.origin}/video/${videoId}`).then(()=>alert('Video link copied!'));
  }

  function toggleComments(el) {
    const panel = el.closest('.video-post').querySelector('.comments-panel');
    document.querySelectorAll('.comments-panel').forEach(p => p !== panel && p.classList.remove('active'));
    panel.classList.toggle('active');
  }

  function postComment(btn) {
    const panel = btn.closest('.comments-panel');
    const input = panel.querySelector('input');
    const videoId = input.dataset.videoId;
    const text = input.value.trim();
    if (!text) return;

    const list = panel.querySelector('.comments-list');
    const div = document.createElement('div');
    div.className = 'comment';
    div.innerHTML = `<strong>@you</strong> ${text}`;
    list.appendChild(div);

    const countEl = document.querySelector(`.comment-count-${videoId}`);
    if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;

    fetch('{{ route("comment.store") }}', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body:JSON.stringify({ video_id:videoId, content:text })
    }).catch(console.log);

    input.value=''; 
    list.scrollTop=list.scrollHeight;
  }

  // ===== VIDEO OBSERVER =====
  const videoObserver = new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
      const video = entry.target.querySelector('video');
      const icon = entry.target.querySelector('.play-pause-animation i');
      if (!video || !video.src) return;
      if (entry.isIntersecting) { 
        video.play().catch(()=>{video.muted=true; video.play().catch(console.error);}); 
        icon?.classList.replace('fa-play','fa-pause'); 
      } else { 
        video.pause(); 
        icon?.classList.replace('fa-pause','fa-play'); 
      }
    });
  }, { threshold:0.8 });

  document.querySelectorAll('.video-post').forEach(post=>videoObserver.observe(post));

  // Hide skeleton after 2 seconds
  showSkeleton();
  setTimeout(hideSkeleton,2000);

  // Menu navigation
  document.querySelectorAll('.menu a').forEach(link=>{
    link.addEventListener('click',function(e){
      if (this.classList.contains('active') || this.getAttribute('href')==='#') return;
      e.preventDefault(); 
      showSkeleton();
      setTimeout(()=>window.location.href=this.getAttribute('href'),500);
    });
  });
});
</script>
</body>
</html>