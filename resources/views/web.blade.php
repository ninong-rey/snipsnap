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
    /* ... (keep all your existing CSS) ... */

    /* ==== COMMENTS MODAL - UPDATED POSITION ==== */
    .comments-modal {
      position: fixed;
      top: 50%;
      left: calc(50% + 200px); /* Move to right of video */
      transform: translate(-50%, -50%) scale(0);
      width: 380px;
      max-width: 90vw;
      height: 70vh;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      z-index: 1000;
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      margin-left: 1%; /* Added margin */
    }

    .comments-modal.active {
      transform: translate(-50%, -50%) scale(1);
      opacity: 1;
    }

    .comment-replies {
      margin-left: 20px;
      border-left: 2px solid #f0f0f0;
      padding-left: 15px;
      margin-top: 8px;
    }

    .reply-btn {
      background: none;
      border: none;
      color: var(--accent);
      font-size: 12px;
      cursor: pointer;
      padding: 4px 8px;
      margin-top: 5px;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .reply-btn:hover {
      background: rgba(254, 44, 85, 0.1);
    }

    .reply-form {
      display: none;
      margin-top: 10px;
      margin-left: 20px;
    }

    .reply-form.active {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .reply-input {
      flex: 1;
      border: 1px solid #ddd;
      border-radius: 18px;
      padding: 8px 12px;
      font-size: 12px;
      outline: none;
    }

    .reply-input:focus {
      border-color: var(--accent);
    }

    .send-reply-btn {
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>

<body>
  <!-- Share Toast -->
  <div class="share-toast" id="shareToast">Video link copied!</div>

  <!-- Modal Backdrop -->
  <div class="modal-backdrop" id="modalBackdrop" onclick="closeCommentsModal()"></div>

  <!-- Comments Modal -->
  <div class="comments-modal" id="commentsModal">
    <div class="comments-modal-header">
      Comments
      <button class="close-comments" onclick="closeCommentsModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="comments-modal-list" id="commentsModalList">
      <!-- Comments will be loaded here -->
    </div>
    <div class="comments-modal-input">
      <input type="text" placeholder="Add a comment..." id="commentInput">
      <button onclick="postCommentFromModal()">
        <i class="fa-solid fa-paper-plane"></i>
      </button>
    </div>
  </div>

  <!-- Sidebar and Feed (keep your existing HTML structure) -->
  <!-- ... -->

<script>
document.addEventListener('DOMContentLoaded', function() {
  // ===== GLOBAL VARIABLES =====
  const likedVideos = JSON.parse(localStorage.getItem('likedVideos') || '{}');
  const sharedVideos = JSON.parse(localStorage.getItem('sharedVideos') || '{}');
  const userComments = JSON.parse(localStorage.getItem('userComments') || '{}');
  const userReplies = JSON.parse(localStorage.getItem('userReplies') || '{}');
  let currentVideoId = null;
  let isMuteButtonEnabled = true;
  let currentVolume = 1;
  let lastTapTime = 0;

  // ===== SKELETON LOADER =====
  function showSkeleton() {
    document.getElementById('feedContainer').classList.add('skeleton-loading');
    document.getElementById('sidebar')?.classList.add('skeleton-loading');
  }

  function hideSkeleton() {
    document.getElementById('feedContainer').classList.remove('skeleton-loading');
    document.getElementById('sidebar')?.classList.remove('skeleton-loading');
  }

  showSkeleton();
  setTimeout(hideSkeleton, 2000);

  // ===== PERSISTENT STATE MANAGEMENT =====
  function saveState() {
    localStorage.setItem('likedVideos', JSON.stringify(likedVideos));
    localStorage.setItem('sharedVideos', JSON.stringify(sharedVideos));
    localStorage.setItem('userComments', JSON.stringify(userComments));
    localStorage.setItem('userReplies', JSON.stringify(userReplies));
  }

  // ===== PROFILE NAVIGATION - FIXED 500 ERROR =====
  function goToUserProfile(userIdentifier) {
    console.log('Profile navigation attempt:', userIdentifier);
    
    try {
      // Clean input
      const cleanId = (userIdentifier || '').toString().trim();
      
      if (!cleanId || cleanId === 'undefined' || cleanId === 'null') {
        // Fallback to safe route
        window.location.href = '/profile';
      } else {
        // Use absolute URL to avoid routing issues
        const baseUrl = window.location.origin;
        window.location.href = `${baseUrl}/user/${encodeURIComponent(cleanId)}`;
      }
    } catch (error) {
      console.error('Profile navigation error:', error);
      // Ultimate fallback
      window.location.href = '/profile';
    }
  }

  // ===== DOUBLE TAP HEART - FIXED =====
  function handleVideoTap(overlay, event) {
    const currentTime = new Date().getTime();
    const timeSinceLastTap = currentTime - lastTapTime;
    
    if (timeSinceLastTap < 300 && timeSinceLastTap > 0) {
      // Double tap detected - show heart
      event.preventDefault();
      event.stopPropagation();
      doubleTapLike(overlay, event);
      lastTapTime = 0;
    } else {
      // Single tap - toggle play/pause
      togglePlayPause(overlay);
      lastTapTime = currentTime;
    }
  }

  function doubleTapLike(overlay, event) {
    const videoWrapper = overlay.closest('.video-wrapper');
    const videoPost = overlay.closest('.video-post');
    const videoId = videoPost.dataset.videoId;
    const likeBtn = videoPost.querySelector('.like-btn i');
    const likeCount = videoPost.querySelector('.like-count-' + videoId);
    
    // Get position for heart
    const rect = videoWrapper.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    
    // Create heart at tap position
    createHeart(x, y, videoWrapper);
    
    // Like the video if not already liked
    if (!likedVideos[videoId]) {
      likeBtn.classList.add('liked');
      const currentCount = parseInt(likeCount.textContent) || 0;
      likeCount.textContent = currentCount + 1;
      likedVideos[videoId] = true;
      saveState();
      
      // Send to server
      fetch(`/video/${videoId}/like`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).catch(error => console.log('Like error:', error));
    }
  }

  function createHeart(x, y, container) {
    const heart = document.createElement('i');
    heart.className = 'fa-solid fa-heart heart active';
    heart.style.left = `${x}px`;
    heart.style.top = `${y}px`;
    container.appendChild(heart);
    
    setTimeout(() => {
      if (heart.parentNode) {
        heart.parentNode.removeChild(heart);
      }
    }, 1400);
  }

  // ===== VIDEO CONTROLS =====
  function toggleMute(btn) {
    if (!isMuteButtonEnabled) return;
    
    isMuteButtonEnabled = false;
    setTimeout(() => { isMuteButtonEnabled = true; }, 300);
    
    const video = btn.closest('.video-wrapper').querySelector('video');
    const icon = btn.querySelector('i');
    
    if (!video) return;
    
    video.muted = !video.muted;
    icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
    
    const slider = btn.closest('.volume-container').querySelector('input[type="range"]');
    if (slider) {
      if (video.muted) {
        currentVolume = slider.value;
        slider.value = 0;
      } else {
        slider.value = currentVolume || 1;
        video.volume = currentVolume || 1;
      }
    }
  }

  function changeVolume(slider) {
    const video = slider.closest('.video-wrapper').querySelector('video');
    const icon = slider.closest('.volume-container').querySelector('.volume-btn i');
    
    if (!video) return;
    
    const volume = parseFloat(slider.value);
    video.volume = volume;
    currentVolume = volume;
    video.muted = (volume === 0);
    icon.className = video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
  }

  function togglePlayPause(overlay) {
    const videoWrapper = overlay.closest('.video-wrapper');
    const video = videoWrapper.querySelector('video');
    const animation = videoWrapper.querySelector('.play-pause-animation');
    const icon = animation.querySelector('i');

    if (!video) return;

    animation.classList.remove('active');
    void animation.offsetWidth;
    animation.classList.add('active');

    if (video.paused) {
      video.play().catch(error => {
        console.log('Play failed, trying with mute:', error);
        video.muted = true;
        video.play().catch(console.error);
      });
      icon.className = 'fas fa-pause';
    } else {
      video.pause();
      icon.className = 'fas fa-play';
    }
  }

  // ===== LIKE SYSTEM - PERMANENT =====
  function toggleLike(btn, videoId) {
    const likeIcon = btn.querySelector('i');
    const likeCount = document.querySelector(`.like-count-${videoId}`);
    const videoWrapper = btn.closest('.video-post').querySelector('.video-wrapper');
    
    if (!likedVideos[videoId]) {
      // Like the video
      likeIcon.classList.add('liked');
      
      // Create heart animation
      const rect = videoWrapper.getBoundingClientRect();
      createHeart(rect.width / 2, rect.height / 2, videoWrapper);
      
      // Update count
      const currentCount = parseInt(likeCount.textContent) || 0;
      likeCount.textContent = currentCount + 1;
      likedVideos[videoId] = true;
      
      // Send to server
      fetch(`/video/${videoId}/like`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).catch(error => console.log('Like error:', error));
    } else {
      // Unlike the video
      likeIcon.classList.remove('liked');
      const currentCount = parseInt(likeCount.textContent) || 0;
      likeCount.textContent = Math.max(0, currentCount - 1);
      delete likedVideos[videoId];
      
      fetch(`/video/${videoId}/unlike`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).catch(error => console.log('Unlike error:', error));
    }
    saveState();
  }

  // ===== SHARE SYSTEM - PERMANENT =====
  function shareVideo(videoId) {
    const countEl = document.querySelector(`.share-count-${videoId}`);
    
    // Update count if not already shared
    if (!sharedVideos[videoId] && countEl) {
      const currentCount = parseInt(countEl.textContent) || 0;
      countEl.textContent = currentCount + 1;
      sharedVideos[videoId] = true;
      saveState();
      
      // Send to server
      fetch(`/video/${videoId}/share`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        }
      }).catch(error => console.log('Share error:', error));
    }
    
    // Copy link to clipboard
    const videoUrl = `${window.location.origin}/video/${videoId}`;
    navigator.clipboard.writeText(videoUrl).then(() => {
      showShareToast();
    }).catch(() => {
      prompt('Copy this link:', videoUrl);
      showShareToast();
    });
  }

  function showShareToast() {
    const toast = document.getElementById('shareToast');
    toast.classList.remove('active');
    void toast.offsetWidth;
    toast.classList.add('active');
    
    setTimeout(() => {
      toast.classList.remove('active');
    }, 2000);
  }

  // ===== COMMENTS SYSTEM WITH REPLIES =====
  function openCommentsModal(videoId) {
    currentVideoId = videoId;
    const modal = document.getElementById('commentsModal');
    const backdrop = document.getElementById('modalBackdrop');
    const commentInput = document.getElementById('commentInput');
    
    loadComments(videoId);
    
    backdrop.classList.add('active');
    modal.classList.add('active');
    
    setTimeout(() => {
      commentInput.focus();
    }, 300);
  }

  function closeCommentsModal() {
    const modal = document.getElementById('commentsModal');
    const backdrop = document.getElementById('modalBackdrop');
    
    modal.classList.remove('active');
    backdrop.classList.remove('active');
    
    document.getElementById('commentInput').value = '';
    currentVideoId = null;
  }

  function loadComments(videoId) {
    const commentsList = document.getElementById('commentsModalList');
    commentsList.innerHTML = '';
    
    // Load server comments
    const serverComments = @json($video->comments->where('parent_id', null)->map(function($comment) use ($video) {
        return [
            'id' => $comment->id,
            'user' => $comment->user ? '@' . ($comment->user->username ?? $comment->user->name) : '@deleted_user',
            'content' => $comment->content ?? '',
            'video_id' => $video->id
        ];
    }));
    
    // Load user comments from localStorage
    const localComments = userComments[videoId] || [];
    const localReplies = userReplies[videoId] || {};
    
    // Combine and display all comments
    [...serverComments, ...localComments].forEach(comment => {
      const commentDiv = createCommentElement(comment, localReplies[comment.id] || []);
      commentsList.appendChild(commentDiv);
    });
    
    commentsList.scrollTop = commentsList.scrollHeight;
  }

  function createCommentElement(comment, replies) {
    const commentDiv = document.createElement('div');
    commentDiv.className = 'comment-item';
    commentDiv.dataset.commentId = comment.id;
    
    const hasReplies = replies && replies.length > 0;
    
    commentDiv.innerHTML = `
      <strong>${comment.user}</strong> ${comment.content}
      <div style="margin-top: 8px;">
        <button class="reply-btn" onclick="toggleReplyForm('${comment.id}')">
          Reply
        </button>
        ${hasReplies ? `
          <button class="reply-btn" onclick="toggleReplies('${comment.id}')">
            Show replies (${replies.length})
          </button>
        ` : ''}
      </div>
      <div class="reply-form" id="replyForm-${comment.id}">
        <input type="text" class="reply-input" placeholder="Write a reply..." id="replyInput-${comment.id}">
        <button class="send-reply-btn" onclick="postReply('${comment.id}')">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
      <div class="comment-replies" id="replies-${comment.id}" style="display: none;">
        ${replies.map(reply => `
          <div class="comment-item">
            <strong>${reply.user}</strong> ${reply.content}
          </div>
        `).join('')}
      </div>
    `;
    
    return commentDiv;
  }

  function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`replyForm-${commentId}`);
    replyForm.classList.toggle('active');
    
    if (replyForm.classList.contains('active')) {
      const replyInput = document.getElementById(`replyInput-${commentId}`);
      setTimeout(() => replyInput.focus(), 100);
    }
  }

  function toggleReplies(commentId) {
    const repliesDiv = document.getElementById(`replies-${commentId}`);
    const toggleBtn = repliesDiv.previousElementSibling.querySelector('.reply-btn:nth-child(2)');
    
    if (repliesDiv.style.display === 'none') {
      repliesDiv.style.display = 'block';
      toggleBtn.textContent = 'Hide replies';
    } else {
      repliesDiv.style.display = 'none';
      toggleBtn.textContent = 'Show replies';
    }
  }

  function postReply(commentId) {
    const replyInput = document.getElementById(`replyInput-${commentId}`);
    const text = replyInput.value.trim();
    
    if (!text || !currentVideoId) return;

    // Save reply to localStorage
    if (!userReplies[currentVideoId]) {
      userReplies[currentVideoId] = {};
    }
    if (!userReplies[currentVideoId][commentId]) {
      userReplies[currentVideoId][commentId] = [];
    }
    
    userReplies[currentVideoId][commentId].push({
      user: '@you',
      content: text
    });
    saveState();

    // Update UI
    const repliesDiv = document.getElementById(`replies-${commentId}`);
    const replyElement = document.createElement('div');
    replyElement.className = 'comment-item';
    replyElement.innerHTML = `<strong>@you</strong> ${text}`;
    repliesDiv.appendChild(replyElement);

    // Update reply count
    const toggleBtn = repliesDiv.previousElementSibling.querySelector('.reply-btn:nth-child(2)');
    if (toggleBtn) {
      const replyCount = userReplies[currentVideoId][commentId].length;
      toggleBtn.textContent = `Hide replies (${replyCount})`;
    }

    // Clear input and hide form
    replyInput.value = '';
    document.getElementById(`replyForm-${commentId}`).classList.remove('active');

    // Show replies if hidden
    if (repliesDiv.style.display === 'none') {
      repliesDiv.style.display = 'block';
    }
  }

  function postCommentFromModal() {
    const input = document.getElementById('commentInput');
    const text = input.value.trim();
    
    if (!text || !currentVideoId) return;

    const commentsList = document.getElementById('commentsModalList');
    const commentId = 'local_' + Date.now();
    
    // Create new comment element
    const commentDiv = createCommentElement({
      id: commentId,
      user: '@you',
      content: text,
      video_id: currentVideoId
    }, []);
    commentsList.appendChild(commentDiv);

    // Save to localStorage
    if (!userComments[currentVideoId]) {
      userComments[currentVideoId] = [];
    }
    userComments[currentVideoId].push({
      id: commentId,
      user: '@you',
      content: text,
      video_id: currentVideoId
    });
    saveState();

    // Update comment count
    const countEl = document.querySelector(`.comment-count-${currentVideoId}`);
    if (countEl) {
      countEl.textContent = parseInt(countEl.textContent) + 1;
    }

    // Send to server
    fetch('{{ route("comment.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        video_id: currentVideoId,
        content: text
      })
    }).catch(error => {
      console.log('Comment save error:', error);
    });

    // Clear input and scroll
    input.value = '';
    commentsList.scrollTop = commentsList.scrollHeight;
  }

  // ===== INITIALIZATION =====
  function initializeVideoInteractions() {
    // Restore all states on page load
    document.querySelectorAll('.video-post').forEach(post => {
      const videoId = post.dataset.videoId;
      
      // Restore likes
      const likeBtn = post.querySelector('.like-btn i');
      if (likedVideos[videoId] && likeBtn) {
        likeBtn.classList.add('liked');
      }
      
      // Restore share counts (if needed)
      // Note: Share counts are primarily server-side
    });

    // Set up video observers
    document.querySelectorAll('.video-post').forEach(post => {
      videoObserver.observe(post);
    });

    // Set up comment input enter key
    document.getElementById('commentInput')?.addEventListener('keypress', function(event) {
      if (event.key === 'Enter') {
        postCommentFromModal();
      }
    });

    // Set up escape key to close modal
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeCommentsModal();
      }
    });
  }

  // ===== VIDEO OBSERVER =====
  const videoObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      const video = entry.target.querySelector('video');
      const animation = entry.target.querySelector('.play-pause-animation');
      const icon = animation?.querySelector('i');
      
      if (!video) return;
      
      if (entry.isIntersecting) {
        video.play().catch(error => {
          console.log('Auto-play failed, trying with mute:', error);
          video.muted = true;
          video.play().catch(console.error);
        });
        if (icon) icon.className = 'fas fa-pause';
      } else {
        video.pause();
        if (icon) icon.className = 'fas fa-play';
      }
    });
  }, { threshold: 0.8 });

  // ===== ATTACH FUNCTIONS TO WINDOW =====
  window.togglePlayPause = togglePlayPause;
  window.toggleMute = toggleMute;
  window.changeVolume = changeVolume;
  window.toggleLike = toggleLike;
  window.openCommentsModal = openCommentsModal;
  window.closeCommentsModal = closeCommentsModal;
  window.shareVideo = shareVideo;
  window.postCommentFromModal = postCommentFromModal;
  window.goToUserProfile = goToUserProfile;
  window.handleVideoTap = handleVideoTap;
  window.toggleReplyForm = toggleReplyForm;
  window.toggleReplies = toggleReplies;
  window.postReply = postReply;

  // ===== INITIALIZE =====
  setTimeout(() => {
    initializeVideoInteractions();
  }, 1000);
});
</script>
</body>
</html>