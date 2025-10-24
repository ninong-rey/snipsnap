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

    .notification-dot {
      background: var(--accent);
      color: #fff;
      border-radius: 50%;
      font-size: 10px;
      padding: 2px 5px;
      margin-left: auto;
    }

    /* Feed */
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
      flex-direction: column;
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
      transition: opacity 0.3s ease;
    }

    /* Video controls - top right */
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
      transition: background 0.2s ease;
    }

    .control-btn:hover {
      background: rgba(0, 0, 0, 0.7);
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

    /* Play/Pause animation */
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

    /* Floating heart */
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

    /* Buttons & Caption */
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
      transition: transform 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }

    .action-btn:hover { transform: scale(1.2); }
    .liked { color: var(--accent); }

    .action-count {
      font-size: 12px;
      font-weight: 600;
      color: #fff;
    }

    /* NEW: User avatar above heart */
    .user-avatar-btn {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      border: 2px solid #fff;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s;
      background: #fff;
    }

    .user-avatar-btn:hover {
      transform: scale(1.1);
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
    }

    .overlay { position: absolute; inset: 0; cursor: pointer; }

    /* Comments Panel */
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

    .comments-panel.active { right: 0; }

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

    .comment strong { color: var(--accent); }

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
  </style>
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <div class="logo">
        <img src="{{ secure_asset('image/snipsnap.png') }}" alt="SnipSnap">
        SnipSnap
      </div>

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Search">
      </div>

      <div class="menu">
        <a href="{{ route('my-web') }}" class="active"><i class="fa-solid fa-house"></i>For You</a>
        <a href="{{ route('explore.users') }}"><i class="fa-regular fa-compass"></i>Explore</a>
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

  @if(request()->has('uploaded_video'))
  <div id="uploadOverlay" style="position: fixed; top: 20px; right: 20px; width: 160px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; background: #000;">
    <video id="processingVideo" src="{{ request()->get('uploaded_video') }}" autoplay muted loop style="width:100%; display:block;"></video>
    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;">
      <i class="fas fa-spinner fa-spin" style="color:#fff; font-size:36px;"></i>
    </div>
  </div>
  <script>
    setTimeout(()=>document.getElementById('uploadOverlay').remove(), 5000);
  </script>
  @endif

  <!-- Feed -->
  <main class="feed-container">
    @foreach($videos as $video)
    <div class="video-post" data-video-id="{{ $video->id }}">
      <div class="video-wrapper">
    @php
        $videoUrl = $video->url ?? null;
        if (!$videoUrl && !empty($video->file_path)) {
            $videoUrl = asset('storage/' . $video->file_path);
        }
    @endphp

    @if($videoUrl)
        <video src="{{ asset('storage/' . ($video->file_path ?? $video->url)) }}" controls loop playsinline preload="metadata"></video>

        @else
        <div style="width:100%;height:100%;background:#000;display:flex;align-items:center;justify-content:center;color:#fff;">
            Video not available
        </div>
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

    <!-- Actions (like, comment, share) -->
    <div class="actions">
        @php $videoUser = $video->user; @endphp
        @if($videoUser)
        <div class="user-avatar-btn" onclick="goToUserProfile('{{ $videoUser->username ?? $videoUser->id }}')">
            <img src="{{ $videoUser->avatar ? asset('storage/' . $videoUser->avatar) : asset('default-avatar.png') }}" 
                 alt="{{ $videoUser->username ?? $videoUser->name }}">
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

    <!-- Caption -->
    <div class="caption">
        <strong>{{ $videoUser ? '@' . ($videoUser->username ?? $videoUser->name) : '@deleted_user' }}</strong><br>
        {{ $video->caption ?? '' }}
    </div>
</div>


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
    const likedVideos = new Set();
    let lastTapTime = 0;

    function goToUserProfile(userIdentifier) {
      if (userIdentifier && isNaN(userIdentifier)) {
        window.location.href = `/user/${userIdentifier}`;
      } else {
        window.location.href = `/profile`;
      }
    }

    function togglePlayPause(overlay, event) {
      const currentTime = new Date().getTime();
      const timeSinceLastTap = currentTime - lastTapTime;
      if (timeSinceLastTap < 300) {
        doubleTapLike(overlay, event);
        lastTapTime = 0;
        return;
      }
      lastTapTime = currentTime;
      
      const videoWrapper = overlay.closest('.video-wrapper');
      const video = videoWrapper.querySelector('video');
      const animation = videoWrapper.querySelector('.play-pause-animation');
      const icon = animation.querySelector('i');

      animation.classList.remove('active'); 
      void animation.offsetWidth; 
      animation.classList.add('active');

      if (video.paused) {
        video.play().catch(()=>{video.muted=true; video.play()});
        icon.classList.replace('fa-play','fa-pause');
      } else {
        video.pause();
        icon.classList.replace('fa-pause','fa-play');
      }
    }

    function toggleMute(btn) {
      const video = btn.closest('.video-wrapper').querySelector('video');
      const icon = btn.querySelector('i');
      video.muted = !video.muted;
      icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
    }

    function changeVolume(slider) {
      const video = slider.closest('.video-wrapper').querySelector('video');
      const icon = slider.closest('.volume-container').querySelector('i');
      video.volume = slider.value;
      video.muted = slider.value == 0;
      icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
    }

    function createHeart(x, y, container) {
      const heart = document.createElement('i');
      heart.className = 'fa-solid fa-heart heart active';
      heart.style.left = `${x}px`;
      heart.style.top = `${y}px`;
      heart.style.transform = 'translate(-50%, -50%)';
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
      countEl.textContent = parseInt(countEl.textContent)+1;
      fetch(`/video/${videoId}/like`, {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}}).catch(console.log);
    }

    function shareVideo(videoId) {
      const countEl = document.querySelector(`.share-count-${videoId}`);
      countEl.textContent = parseInt(countEl.textContent)+1;
      fetch(`/video/${videoId}/share`, {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}});
      navigator.clipboard.writeText(`${window.location.origin}/video/${videoId}`).then(()=>alert('Video link copied!'));
    }

    function toggleComments(el) {
      const panel = el.closest('.video-post').querySelector('.comments-panel');
      document.querySelectorAll('.comments-panel').forEach(p=>p!==panel&&p.classList.remove('active'));
      panel.classList.toggle('active');
    }

    function postComment(btn) {
      const panel = btn.closest('.comments-panel');
      const input = panel.querySelector('input');
      const videoId = input.dataset.videoId;
      const text = input.value.trim();
      if (!text) return;
      const list = panel.querySelector('.comments-list');
      const div = document.createElement('div'); div.className='comment'; div.innerHTML=`<strong>@you</strong> ${text}`;
      list.appendChild(div);
      document.querySelector(`.comment-count-${videoId}`).textContent = parseInt(document.querySelector(`.comment-count-${videoId}`).textContent)+1;
      fetch('{{ route("comment.store") }}', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({video_id:videoId,content:text,_token:'{{ csrf_token() }}'})}).catch(console.log);
      input.value=''; list.scrollTop=list.scrollHeight;
    }

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        const video = entry.target.querySelector('video');
        const icon = entry.target.querySelector('.play-pause-animation i');
        if (entry.isIntersecting) {
          video.play().catch(()=>{video.muted=true; video.play()});
          icon.classList.replace('fa-play','fa-pause');
        } else {
          video.pause();
          icon.classList.replace('fa-pause','fa-play');
        }
      });
    }, { threshold: 0.8 });

    document.querySelectorAll('.video-post').forEach(post => observer.observe(post));
  </script>
</body>
</html>
