@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explorer - User</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    :root { --accent: #ff4b2b; --accent-hover: #ff6b4b; }
    body { font-family: Arial, sans-serif; margin:0; padding:0; background:#f5f5f5; }
    .profile-container { max-width:600px; margin:50px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); position:relative; }
    .profile-header { display:flex; gap:15px; align-items:center; }
    .profile-header img { width:80px; height:80px; border-radius:50%; object-fit:cover; }
    .profile-info { flex:1; }
    .profile-info h2 { margin:0; font-size:20px; }
    .profile-info p { margin:4px 0; color:#666; font-size:14px; }
    .profile-stats { display:flex; gap:20px; margin-top:10px; font-size:14px; }
    .follow-btn { padding:8px 16px; border:none; border-radius:8px; background:var(--accent); color:#fff; cursor:pointer; transition:0.3s; }
    .follow-btn:hover { background:var(--accent-hover); }
    /* Skeleton */
    .skeleton { background:#ddd; border-radius:4px; }
    .skeleton-circle { width:80px; height:80px; border-radius:50%; background:#ddd; }
    .skeleton-line { height:16px; background:#ddd; margin:6px 0; width:100%; max-width:150px; }
    /* Blur loader */
    .blur-loader { display:none; position:absolute; top:0; left:0; width:100%; height:100%; backdrop-filter:blur(4px); background:rgba(255,255,255,0.5); z-index:10; align-items:center; justify-content:center; font-size:24px; color:#333; }
  </style>
</head>
<body>

<div class="profile-container">
  <!-- Blur Loader for Follow button -->
  <div id="blurLoader" class="blur-loader"><i class="fa-solid fa-spinner fa-spin"></i></div>

  <!-- Skeleton Loader -->
  <div id="skeletonLoader">
    <div class="profile-header">
      <div class="skeleton-circle"></div>
      <div class="profile-info">
        <div class="skeleton-line" style="width:120px;"></div>
        <div class="skeleton-line" style="width:80px;"></div>
        <div class="profile-stats">
          <div class="skeleton-line" style="width:40px;"></div>
          <div class="skeleton-line" style="width:40px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Actual Profile Content -->
  <div id="profileContent" style="display:none;">
    <div class="profile-header">
      <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://via.placeholder.com/80' }}" alt="{{ $user->name }}">
      <div class="profile-info">
        <h2>{{ '@'.$user->username }}</h2>
        <p>{{ $user->bio }}</p>
        <div class="profile-stats">
          <span>{{ $user->followers_count }} Followers</span>
          <span>{{ $user->following_count }} Following</span>
        </div>
      </div>
    </div>

    @if(auth()->user()->id !== $user->id)
      <button id="followBtn" class="follow-btn" data-user-id="{{ $user->id }}">
        {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
      </button>
    @endif
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Show actual content after load
  setTimeout(() => {
    document.getElementById('skeletonLoader').style.display = 'none';
    document.getElementById('profileContent').style.display = 'block';
  }, 500); // simulate data load

  // Follow/unfollow button
  const followBtn = document.getElementById('followBtn');
  const blurLoader = document.getElementById('blurLoader');

  if(followBtn) {
    followBtn.addEventListener('click', function() {
      const userId = this.dataset.userId;
      blurLoader.style.display = 'flex'; // show blur loader

      fetch(`/follow/${userId}`, { 
        method:'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        blurLoader.style.display = 'none'; // hide blur loader
        if(data.status === 'followed') followBtn.textContent = 'Unfollow';
        else if(data.status === 'unfollowed') followBtn.textContent = 'Follow';
      })
      .catch(err => {
        blurLoader.style.display = 'none';
        alert('Something went wrong.');
      });
    });
  }
});
</script>

</body>
</html>
