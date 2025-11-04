<?php
// TEMPORARY ERROR DISPLAY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
?>
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
      padding-left: 40px;
    }

    .video-post {
      width: 100%;
      height: 100vh;
      scroll-snap-align: start;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      background: #fff;
      position: relative;
    }

    .video-wrapper {
      position: relative;
      width: 100%;
      max-width: 380px;
      aspect-ratio: 9 / 16;
      background: #000;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
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

    .volume-container {
      display: flex;
      align-items: center;
      gap: 8px;
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
      z-index: 10;
    }

    .heart.active {
      animation: floatHeart 1.4s cubic-bezier(0.17, 0.67, 0.83, 0.67) forwards;
    }

    @keyframes floatHeart {
      0% { 
        transform: scale(0) translateY(0) rotate(0deg); 
        opacity: 0; 
      }
      20% { 
        transform: scale(1.2) translateY(-20px) rotate(5deg); 
        opacity: 1; 
      }
      40% { 
        transform: scale(1.1) translateY(-40px) rotate(10deg); 
        opacity: 0.9; 
      }
      60% { 
        transform: scale(1) translateY(-80px) rotate(5deg); 
        opacity: 0.7; 
      }
      80% { 
        transform: scale(0.9) translateY(-120px) rotate(0deg); 
        opacity: 0.4; 
      }
      100% { 
        transform: scale(0.8) translateY(-150px) rotate(-5deg); 
        opacity: 0; 
      }
    }

    /* ==== COMMENTS MODAL ==== */
    .comments-modal {
      position: fixed;
      top: 50%;
      left: calc(50% + 200px);
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
      margin-left: 1%;
    }

    .comments-modal.active {
      transform: translate(-50%, -50%) scale(1);
      opacity: 1;
    }

    .comments-modal-header {
      padding: 20px;
      border-bottom: 1px solid #eee;
      font-weight: 600;
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fafafa;
    }

    .close-comments {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #666;
      transition: color 0.3s ease;
    }

    .close-comments:hover {
      color: var(--accent);
    }

    .comments-modal-list {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      background: #fff;
    }

    .comment-item {
      padding: 12px 0;
      border-bottom: 1px solid #f5f5f5;
      line-height: 1.4;
    }

    .comment-item strong {
      color: var(--accent);
      font-weight: 600;
    }

    .comment-replies {
      margin-left: 20px;
      border-left: 2px solid #f0f0f0;
      padding-left: 15px;
      margin-top: 8px;
      display: none;
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

    .comments-modal-input {
      border-top: 1px solid #eee;
      padding: 16px;
      display: flex;
      gap: 10px;
      align-items: center;
      background: #fafafa;
    }

    .comments-modal-input input {
      flex: 1;
      border: 1px solid #ddd;
      border-radius: 24px;
      padding: 12px 16px;
      outline: none;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }

    .comments-modal-input input:focus {
      border-color: var(--accent);
    }

    .comments-modal-input button {
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 42px;
      height: 42px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .comments-modal-input button:hover {
      background: #e00040;
      transform: scale(1.05);
    }

    /* ==== SHARE TOAST ==== */
    .share-toast {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 16px 24px;
      border-radius: 50px;
      font-weight: 600;
      z-index: 10000;
      opacity: 0;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .share-toast.active {
      animation: shareFloat 2s ease-in-out forwards;
    }

    @keyframes shareFloat {
      0% { 
        transform: translate(-50%, -50%) scale(0); 
        opacity: 0; 
      }
      20% { 
        transform: translate(-50%, -60%) scale(1); 
        opacity: 1; 
      }
      80% { 
        transform: translate(-50%, -60%) scale(1); 
        opacity: 1; 
      }
      100% { 
        transform: translate(-50%, -80%) scale(0.8); 
        opacity: 0; 
      }
    }

    /* ==== ACTIONS ==== */
    .actions {
      position: absolute;
      right: 10px;
      bottom: 80px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
      z-index: 10;
    }

    .action-btn {
      color: #fff;
      font-size: 24px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      position: relative;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .action-btn:hover { 
      transform: scale(1.15); 
    }

    .liked { 
      color: var(--accent) !important; 
      animation: heartBeat 0.6s ease;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      25% { transform: scale(1.3); }
      50% { transform: scale(1.1); }
      75% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    .action-count {
      font-size: 12px;
      font-weight: 600;
      color: #fff;
      text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    .user-avatar-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid #fff;
      overflow: hidden;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #fff;
      position: relative;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .user-avatar-btn:hover {
      transform: scale(1.1);
      border-color: var(--accent);
      box-shadow: 0 4px 12px rgba(254, 44, 85, 0.3);
    }

    .user-avatar-btn img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* ==== VIDEO INFO ==== */
    .video-info {
      position: absolute;
      left: 15px;
      bottom: 80px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: calc(100% - 120px);
      z-index: 5;
    }

    .user-info {
      display: flex;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 8px 12px;
      border-radius: 16px;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(10px);
    }

    .username {
      color: #fff;
      font-weight: 600;
      font-size: 14px;
    }

    .caption {
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      line-height: 1.3;
      padding: 10px 12px;
      border-radius: 10px;
      max-height: 100px;
      overflow-y: auto;
    }

    .caption::-webkit-scrollbar {
      width: 3px;
    }

    .caption::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 2px;
    }

    .overlay { 
      position: absolute; 
      inset: 0; 
      cursor: pointer; 
      z-index: 2;
    }

    /* ==== MODAL BACKDROP ==== */
    .modal-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .modal-backdrop.active {
      opacity: 1;
      pointer-events: all;
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

    /* ==== EMPTY STATE ==== */
    .empty-state {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-direction: column;
      text-align: center;
      padding: 20px;
    }

    .empty-state i {
      font-size: 72px;
      color: #ccc;
      margin-bottom: 20px;
    }

    .empty-state h3 {
      color: #666;
      margin-bottom: 10px;
      font-size: 20px;
    }

    .empty-state p {
      color: #999;
      max-width: 300px;
      line-height: 1.5;
      margin-bottom: 20px;
    }

    .empty-state a {
      background: var(--accent);
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .empty-state a:hover {
      background: #e00040;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(254, 44, 85, 0.3);
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

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div>
      <div class="logo">
        <img src="{{ asset('image/snipsnap.png') }}" alt="SnipSnap" onerror="this.style.display='none'">
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
    @if($videos->count() > 0)
      @foreach($videos as $video)
      @php $videoUser = $video->user ?? null; @endphp
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
          <div class="overlay" onclick="handleVideoTap(this, event)"></div>

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

          <!-- Video Info -->
          <div class="video-info">
            @if($videoUser)
            <div class="user-info" onclick="goToUserProfile('{{ $videoUser->username ?? $videoUser->id }}')">
              <div class="username">
              {{ $videoUser->username ?? $videoUser->name ?? 'Unknown User' }}
           </div>
            </div>
            @else
            <div class="user-info">
              <div class="username">@unknown_user</div>
            </div>
            @endif
            <div class="caption">
              {{ $video->caption ?? '' }}
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
            <div class="action-btn" onclick="openCommentsModal({{ $video->id }})">
              <i class="fa-solid fa-comment"></i>
              <span class="action-count comment-count-{{ $video->id }}">{{ $video->comments_count ?? 0 }}</span>
            </div>
            <div class="action-btn" onclick="shareVideo({{ $video->id }})">
              <i class="fa-solid fa-share"></i>
              <span class="action-count share-count-{{ $video->id }}">{{ $video->shares_count ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    @else
      <!-- EMPTY STATE -->
      <div class="empty-state">
        <i class="fas fa-video-slash"></i>
        <h3>No videos yet</h3>
        <p>Upload your first video to get started!</p>
        <a href="{{ route('upload') }}">
          <i class="fas fa-plus"></i> Upload Video
        </a>
      </div>
    @endif
  </main>

<script>
/// ===== SAFE PROFILE NAVIGATION (Works on ALL pages) =====
function goToUserProfile(userIdentifier) {
    try {
        const cleanId = (userIdentifier || '').toString().trim();
        
        if (!cleanId || cleanId === 'undefined' || cleanId === 'null') {
            window.location.href = '/profile';
        } else {
            window.location.href = `/user/${encodeURIComponent(cleanId)}`;
        }
    } catch (error) {
        console.error('Profile navigation error:', error);
        window.location.href = '/profile';
    }
}

// Only run video interactions if we're on a video page
if (document.getElementById('feedContainer')) {
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

        // ===== DOUBLE TAP HEART =====
        function handleVideoTap(overlay, event) {
            const currentTime = new Date().getTime();
            const timeSinceLastTap = currentTime - lastTapTime;
            
            if (timeSinceLastTap < 300 && timeSinceLastTap > 0) {
                event.preventDefault();
                event.stopPropagation();
                doubleTapLike(overlay, event);
                lastTapTime = 0;
            } else {
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
            
            const rect = videoWrapper.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            
            createHeart(x, y, videoWrapper);
            
            if (!likedVideos[videoId]) {
                likeBtn.classList.add('liked');
                const currentCount = parseInt(likeCount.textContent) || 0;
                likeCount.textContent = currentCount + 1;
                likedVideos[videoId] = true;
                saveState();
                
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
                likeIcon.classList.add('liked');
                
                const rect = videoWrapper.getBoundingClientRect();
                createHeart(rect.width / 2, rect.height / 2, videoWrapper);
                
                const currentCount = parseInt(likeCount.textContent) || 0;
                likeCount.textContent = currentCount + 1;
                likedVideos[videoId] = true;
                
                fetch(`/video/${videoId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).catch(error => console.log('Like error:', error));
            } else {
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
            
            if (!sharedVideos[videoId] && countEl) {
                const currentCount = parseInt(countEl.textContent) || 0;
                countEl.textContent = currentCount + 1;
                sharedVideos[videoId] = true;
                saveState();
                
                fetch(`/video/${videoId}/share`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).catch(error => console.log('Share error:', error));
            }
            
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
            
            const serverComments = @json(($video->comments ?? collect())->where('parent_id', null)->map(function($comment) use ($video) {
    return [
        'id' => $comment->id ?? null,
        'user' => ($comment->user ? '@' . ($comment->user->username ?? $comment->user->name ?? 'deleted_user') : '@deleted_user'),
        'content' => $comment->content ?? '',
        'video_id' => $video->id
    ];
}));
            
            // Load user comments from localStorage
            const localComments = userComments[videoId] || [];
            const localReplies = userReplies[videoId] || {};
            
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

            const repliesDiv = document.getElementById(`replies-${commentId}`);
            const replyElement = document.createElement('div');
            replyElement.className = 'comment-item';
            replyElement.innerHTML = `<strong>@you</strong> ${text}`;
            repliesDiv.appendChild(replyElement);

            const toggleBtn = repliesDiv.previousElementSibling.querySelector('.reply-btn:nth-child(2)');
            if (toggleBtn) {
                const replyCount = userReplies[currentVideoId][commentId].length;
                toggleBtn.textContent = `Hide replies (${replyCount})`;
            }

            replyInput.value = '';
            document.getElementById(`replyForm-${commentId}`).classList.remove('active');

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
            
            const commentDiv = createCommentElement({
                id: commentId,
                user: '@you',
                content: text,
                video_id: currentVideoId
            }, []);
            commentsList.appendChild(commentDiv);

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

            const countEl = document.querySelector(`.comment-count-${currentVideoId}`);
            if (countEl) {
                countEl.textContent = parseInt(countEl.textContent) + 1;
            }

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

            input.value = '';
            commentsList.scrollTop = commentsList.scrollHeight;
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

        // ===== ATTACH FUNCTIONS TO WINDOW =====
        window.togglePlayPause = togglePlayPause;
        window.toggleMute = toggleMute;
        window.changeVolume = changeVolume;
        window.toggleLike = toggleLike;
        window.openCommentsModal = openCommentsModal;
        window.closeCommentsModal = closeCommentsModal;
        window.shareVideo = shareVideo;
        window.postCommentFromModal = postCommentFromModal;
        window.handleVideoTap = handleVideoTap;
        window.toggleReplyForm = toggleReplyForm;
        window.toggleReplies = toggleReplies;
        window.postReply = postReply;

        // ===== INITIALIZE =====
        setTimeout(() => {
            initializeVideoInteractions();
        }, 1000);
    });
}
</script>

</body>
</html>
<?php
} catch (Exception $e) {
    // This will show the actual error
    echo "<h1>ERROR FOUND:</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Trace:</strong> " . $e->getTraceAsString() . "</p>";
}
?>