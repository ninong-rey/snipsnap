<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipSnap Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --accent: #fe2c55;
            --bg: #fff;
            --text: #000;
            --muted: #888;
            --light-bg: #f8f8f8;
        }
        * {
            box-sizing: border-box;
            margin: 0;  
            padding: 0;
        }
        body {
            font-family: Inter, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
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

        /* ==== PROFILE CONTENT SKELETON ==== */
        .profile-container.skeleton-loading .profile-avatar,
        .profile-container.skeleton-loading .username,
        .profile-container.skeleton-loading .name,
        .profile-container.skeleton-loading .stat-value,
        .profile-container.skeleton-loading .stat-label,
        .profile-container.skeleton-loading .profile-bio,
        .profile-container.skeleton-loading .btn,
        .profile-container.skeleton-loading .tab-item,
        .profile-container.skeleton-loading .video-preview {
            position: relative;
        }

        /* Hide actual content during skeleton loading */
        .profile-container.skeleton-loading .profile-avatar,
        .profile-container.skeleton-loading .username,
        .profile-container.skeleton-loading .name,
        .profile-container.skeleton-loading .stat-value,
        .profile-container.skeleton-loading .stat-label,
        .profile-container.skeleton-loading .profile-bio,
        .profile-container.skeleton-loading .btn,
        .profile-container.skeleton-loading .tab-item,
        .profile-container.skeleton-loading .video-preview video,
        .profile-container.skeleton-loading .video-stats,
        .profile-container.skeleton-loading .video-overlay {
            opacity: 0;
        }

        /* Skeleton for profile header */
        .profile-container.skeleton-loading .profile-avatar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 50%;
        }

        .profile-container.skeleton-loading .username::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 200px;
            height: 32px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .profile-container.skeleton-loading .name::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 0;
            width: 150px;
            height: 18px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        /* Skeleton for stats */
        .profile-container.skeleton-loading .stat-value::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 30px;
            height: 20px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
        }

        .profile-container.skeleton-loading .stat-label::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            width: 60px;
            height: 14px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
        }

        /* Skeleton for bio */
        .profile-container.skeleton-loading .profile-bio::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
            margin-top: 15px;
        }

        /* Skeleton for buttons */
        .profile-container.skeleton-loading .btn::before {
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
        }

        /* Skeleton for tabs */
        .profile-container.skeleton-loading .tab-item::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 20px;
            background: linear-gradient(90deg, #f0f0f0 25%, #f8f8f8 50%, #f0f0f0 75%);
            background-size: 400% 100%;
            animation: shimmer 2s infinite ease-in-out;
            border-radius: 4px;
        }

        /* Skeleton for video grid */
        .profile-container.skeleton-loading .video-preview::before {
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
            color: var(--text);
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
            color: var(--text);
        }

        .menu { flex-grow: 1; }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
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

        .profile-container {
            margin-left: 260px;
            width: calc(100% - 260px);
            max-width: 800px;
            padding: 40px 30px;
            transition: opacity 0.3s ease;
        }

        .profile-container.skeleton-loading {
            opacity: 0.9;
        }

        .profile-header {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-bg);
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .profile-info {
            flex-grow: 1;
        }
        .username {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 5px 0;
            position: relative;
        }
        .name {
            font-size: 18px;
            color: var(--muted);
            margin-bottom: 10px;
            position: relative;
        }
        .stats {
            display: flex;
            gap: 25px;
            margin: 15px 0;
        }
        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
            position: relative;
        }
        .stat-value {
            font-weight: 700;
            position: relative;
        }
        .stat-label {
            color: var(--muted);
            font-weight: 400;
            font-size: 14px;
            position: relative;
        }
        .profile-bio {
            margin-top: 15px;
            font-size: 16px;
            color: var(--text);
            line-height: 1.4;
            position: relative;
        }
        .profile-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            position: relative;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover {
            background: #e0284d;
        }
        .btn-secondary {
            background: var(--light-bg);
            color: var(--text);
            border: 1px solid #ddd;
        }
        .btn-secondary:hover {
            background: #eee;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .tab-item {
            padding: 12px 20px;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            position: relative;
        }
        .tab-item.active {
            color: var(--text);
            border-bottom: 2px solid var(--text);
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .video-preview {
            position: relative;
            width: 100%;
            padding-top: 177.77%;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
        }
        .video-preview video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
            background: #000;
        }
        .video-preview:hover video {
            transform: scale(1.05);
        }
        .video-preview img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .video-stats {
            position: absolute;
            bottom: 8px;
            left: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            z-index: 2;
        }
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }
        .video-preview:hover .video-overlay {
            opacity: 1;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease forwards;
        }
        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            position: relative;
            transform: scale(0.7);
            opacity: 0;
            animation: zoomFadeIn 0.3s forwards;
        }
        .modal-header {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 22px;
            cursor: pointer;
            color: var(--muted);
        }
        .modal-close:hover {
            color: var(--text);
        }
        .avatar-upload-container {
            position: relative;
            width: 120px;
            margin: 0 auto 20px auto;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid #eee;
        }
        .avatar-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #fff;
            border-radius: 50%;
            padding: 6px;
            cursor: pointer;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-input {
            display: none;
        }
        .form-input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }
        .form-textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        /* Toast Styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        .toast.success {
            background: #4CAF50;
        }
        .toast.error {
            background: #f44336;
        }
        .toast.info {
            background: #2196F3;
        }

        @keyframes zoomFadeIn {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Success Message */
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .sidebar {
                display: none;
            }
            .profile-container {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 20px;
            }
            .profile-actions {
                justify-content: center;
            }
            .video-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .video-grid {
                grid-template-columns: 1fr;
            }
            .stats {
                gap: 15px;
            }
            .profile-container {
                padding: 15px;
            }
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
            <a href="{{ route('my-web') }}"><i class="fa-solid fa-house"></i>For You</a>
            <a href="{{ route('explore.users') }}"><i class="fa-regular fa-compass"></i>Explore</a>
            <a href="{{ route('following.videos') }}"><i class="fa-solid fa-user-group"></i>Following</a>
            <a href="{{ route('friends') }}"><i class="fa-solid fa-user-friends"></i>Friends</a>
            <a href="{{ route('upload') }}"><i class="fa-solid fa-plus-square"></i>Upload</a>
            <a href="{{ route('notifications') }}"><i class="fa-regular fa-comment-dots"></i>Notifications</a>
            <a href="{{ route('messages.index') }}"><i class="fa-regular fa-paper-plane"></i>Messages</a>
            <a href="#"><i class="fa-solid fa-tv"></i>LIVE</a>
            <a href="{{ route('profile.show') }}" class="active"><i class="fa-solid fa-user"></i>Profile</a>
            <a href="#"><i class="fa-solid fa-ellipsis"></i>More</a>
        </div>
    </div>

    <form method="POST" action="{{ route('logout.perform') }}">
        @csrf
        <button style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:14px;">Logout</button>
    </form>
</aside>

<!-- Main Content -->
<div class="profile-container" id="profileContainer">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <img src="{{ $user->avatar ? secure_asset('storage/' . $user->avatar) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiByeD0iNjAiIGZpbGw9IiNGM0YzRjMiLz4KPHN2ZyB4PSIzMCIgeT0iMzAiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSIjOTk5IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTIgMTJjMi4yMSAwIDQtMS43OSA0LTRzLTEuNzktNC00LTQtNCAxLjc5LTQgNCAxLjc5IDQgNCA0em0wIDJjLTIuNjcgMC04IDEuMzQtOCA0djJoMTZ2LTJjMC0yLjY2LTUuMzMtNC04LTR6Ii8+Cjwvc3ZnPgo8L3N2Zz4=' }}" 
             alt="{{ $user->name ?? 'User' }} Avatar" class="profile-avatar">
        
        <div class="profile-info">
            <h2 class="username">@ {{ $user->username ?? $user->name }}</h2>
            <p class="name">{{ $user->name }}</p>

            <div class="stats">
                <div class="stat-item">
                    <span class="stat-value">{{ $user->following_count ?? $user->following()->count() }}</span>
                    <span class="stat-label">Following</span>
                </div>      
                <div class="stat-item">
                    <span class="stat-value" id="followersCount">{{ $user->followers_count ?? $user->followers()->count() }}</span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $user->videos_count ?? $user->videos()->count() }}</span>
                    <span class="stat-label">Videos</span>
                </div>
            </div>

            <div class="profile-actions">
                @if(Auth::id() == $user->id)
                    <button class="btn btn-primary" id="editProfileBtn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                @else
                    <button 
                        id="followBtn"
                        data-user-id="{{ $user->id }}"
                        class="btn {{ Auth::user()->isFollowing($user) ? 'btn-secondary' : 'btn-primary' }}">
                        <i class="fas fa-{{ Auth::user()->isFollowing($user) ? 'check' : 'plus' }}"></i>
                        {{ Auth::user()->isFollowing($user) ? 'Following' : 'Follow' }}
                    </button>

                    <a href="{{ route('messages.show', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i> Message
                    </a>
                @endif
            </div>

            <p class="profile-bio">
                {{ $user->bio ?? 'No bio yet. Tap Edit Profile to add one!' }}
            </p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab-item active" data-tab="videos">
            <i class="fas fa-grip-lines"></i> Videos ({{ $videos->count() ?? 0 }})
        </div>
        <div class="tab-item" data-tab="liked">
            <i class="fas fa-heart"></i> Liked ({{ $likedVideosCount ?? 0 }})
        </div>
    </div>

    <!-- Video Grid -->
    <div class="video-grid" id="videoGrid">
        @if(request()->get('tab', 'videos') === 'videos' || !request()->has('tab'))
            @forelse($videos as $video)
                <div class="video-preview" data-url="{{ route('video.show', $video->id) }}">
                    @if($video->url)
                        @php
                            // Fix video URL - check if it's already a full URL or just a filename
                            $videoUrl = $video->url;
                            if (!str_contains($videoUrl, '://')) {
                                // It's just a filename, prepend storage path
                                $videoUrl = 'storage/' . $videoUrl;
                            }
                        @endphp
                        <video muted loop preload="metadata" playsinline 
                               data-src="{{ secure_asset($videoUrl) }}"
                               poster="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjUzMyIgdmlld0JveD0iMCAwIDMwMCA1MzMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNTMzIiBmaWxsPSIjMDAwIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMjY2LjUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+VmlkZW8gTG9hZGluZy4uLjwvdGV4dD4KPC9zdmc+">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjUzMyIgdmlld0JveD0iMCAwIDMwMCA1MzMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNTMzIiBmaWxsPSIjMDAwIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMjY2LjUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gVmlkZW88L3RleHQ+Cjwvc3ZnPg==" 
                             alt="Video Thumbnail">
                    @endif
                    <div class="video-overlay">
                        <i class="fas fa-play" style="color: white; font-size: 40px;"></i>
                    </div>
                    <div class="video-stats">
                        <i class="fas fa-play"></i>
                        <span>{{ $video->views ?? 0 }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px;">
                    <i class="fas fa-video" style="font-size: 48px; color: var(--muted); margin-bottom: 15px;"></i>
                    <p style="color: var(--muted); font-size: 16px;">No videos uploaded yet.</p>
                    @if(Auth::id() == $user->id)
                        <a href="{{ route('upload') }}" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-upload"></i> Upload Your First Video
                        </a>
                    @endif
                </div>
            @endforelse
        @elseif(request()->get('tab') === 'liked')
            @forelse($likedVideos as $video)
                <div class="video-preview" data-url="{{ route('video.show', $video->id) }}">
                    @if($video->url)
                        @php
                            $videoUrl = $video->url;
                            if (!str_contains($videoUrl, '://')) {
                                $videoUrl = 'storage/' . $videoUrl;
                            }
                        @endphp
                        <video muted loop preload="metadata" playsinline 
                               data-src="{{ secure_asset($videoUrl) }}"
                               poster="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjUzMyIgdmlld0JveD0iMCAwIDMwMCA1MzMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNTMzIiBmaWxsPSIjMDAwIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMjY2LjUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+VmlkZW8gTG9hZGluZy4uLjwvdGV4dD4KPC9zdmc+">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjUzMyIgdmlld0JveD0iMCAwIDMwMCA1MzMiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNTMzIiBmaWxsPSIjMDAwIi8+Cjx0ZXh0IHg9IjE1MCIgeT0iMjY2LjUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gVmlkZW88L3RleHQ+Cjwvc3ZnPg==" 
                             alt="Video Thumbnail">
                    @endif
                    <div class="video-overlay">
                        <i class="fas fa-play" style="color: white; font-size: 40px;"></i>
                    </div>
                    <div class="video-stats">
                        <i class="fas fa-play"></i>
                        <span>{{ $video->views ?? 0 }}</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px;">
                    <i class="fas fa-heart" style="font-size: 48px; color: var(--muted); margin-bottom: 15px;"></i>
                    <p style="color: var(--muted); font-size: 16px;">No liked videos yet.</p>
                    <p style="color: var(--muted); font-size: 14px;">Like some videos to see them here!</p>
                </div>
            @endforelse
        @endif
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="modal-close" id="closeModal">&times;</span>
        <div class="modal-header">Edit Profile</div>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            
            <div class="avatar-upload-container">
                <img id="modalAvatarPreview" 
                     src="{{ $user->avatar ? secure_asset('storage/' . $user->avatar) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiByeD0iNjAiIGZpbGw9IiNGM0YzRjMiLz4KPHN2ZyB4PSIzMCIgeT0iMzAiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSIjOTk5IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTIgMTJjMi4yMSAwIDQtMS43OSA0LTRzLTEuNzktNC00LTQtNCAxLjc5LTQgNCAxLjc5IDQgNCA0em0wIDJjLTIuNjcgMC04IDEuMzQtOCA0djJoMTZ2LTJjMC0yLjY2LTUuMzMtNC04LTR6Ii8+Cjwvc3ZnPgo8L3N2Zz4=' }}" 
                     class="avatar-preview"
                     alt="Avatar Preview">
                <div class="avatar-edit-btn" id="changeAvatarBtn">
                    <i class="fas fa-pen"></i>
                </div>
                <input type="file" name="avatar" id="avatarInput" class="avatar-input" accept="image/*">
            </div>

            <input type="text" name="name" class="form-input" placeholder="Full Name" value="{{ $user->name }}">
            <input type="text" name="username" class="form-input" placeholder="Username" value="{{ $user->username }}">
            <textarea name="bio" class="form-textarea" placeholder="Tell us about yourself...">{{ $user->bio }}</textarea>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <button type="button" class="btn btn-secondary" id="cancelModal">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ===== SKELETON LOADER FUNCTIONS =====
    let loaderTimeout;

    // Show skeleton loading
    function showSkeleton() {
        const profileContainer = document.getElementById('profileContainer');
        const sidebar = document.getElementById('sidebar');
        
        if (profileContainer) {
            profileContainer.classList.add('skeleton-loading');
        }
        if (sidebar) {
            sidebar.classList.add('skeleton-loading');
        }
    }

    // Hide skeleton loading
    function hideSkeleton() {
        const profileContainer = document.getElementById('profileContainer');
        const sidebar = document.getElementById('sidebar');
        
        if (profileContainer) {
            profileContainer.classList.remove('skeleton-loading');
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

    // ===== VIDEO PLAYBACK FUNCTIONALITY =====
    function initVideoGrid() {
        const previews = document.querySelectorAll('.video-preview');
        
        previews.forEach(preview => {
            const video = preview.querySelector('video');
            if (!video) return;
            
            // Set video attributes
            video.muted = true;
            video.playsInline = true;
            video.preload = 'metadata';
            
            // Lazy load video source
            const videoSrc = video.getAttribute('data-src');
            if (videoSrc && !video.src) {
                video.src = videoSrc;
            }
            
            let isPlaying = false;
            let playPromise;
            
            // Handle video loading errors
            video.addEventListener('error', function() {
                console.error('Video loading error:', video.src);
                // Show fallback image
                video.style.display = 'none';
            });
            
            // Handle video load success
            video.addEventListener('loadeddata', function() {
                console.log('Video loaded successfully:', video.src);
            });
            
            // Hover play functionality
            preview.addEventListener('mouseenter', async function() {
                if (isPlaying || !video.src || video.readyState < 3) return;
                
                try {
                    playPromise = video.play();
                    await playPromise;
                    isPlaying = true;
                } catch (error) {
                    console.log('Autoplay failed:', error);
                }
            });
            
            preview.addEventListener('mouseleave', async function() {
                if (!isPlaying) return;
                
                try {
                    if (playPromise) {
                        await playPromise;
                    }
                    video.pause();
                    video.currentTime = 0;
                    isPlaying = false;
                } catch (error) {
                    console.log('Pause error:', error);
                }
            });
            
            // Click handling
            preview.addEventListener('click', function(e) {
                e.preventDefault();
                if (video && isPlaying) {
                    video.pause();
                }
                window.location.href = this.dataset.url;
            });
        });
    }

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initSkeletonLoader();
        initNavigation();
        
        // Initialize video grid after skeleton loader
        setTimeout(() => {
            initVideoGrid();
        }, 2100);
    });

    // =============================
    // Modal functionality
    // =============================
    const editBtn = document.getElementById('editProfileBtn');
    const modal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');

    if (editBtn) {
        editBtn.addEventListener('click', () => modal.style.display = 'flex');
    }
    if (closeModal) {
        closeModal.addEventListener('click', () => modal.style.display = 'none');
    }
    if (cancelModal) {
        cancelModal.addEventListener('click', () => modal.style.display = 'none');
    }

    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    // =============================
    // Avatar upload
    // =============================
    const avatarInput = document.getElementById('avatarInput');
    const modalAvatarPreview = document.getElementById('modalAvatarPreview');
    const changeAvatarBtn = document.getElementById('changeAvatarBtn');

    if (modalAvatarPreview) {
        modalAvatarPreview.addEventListener('click', () => avatarInput.click());
    }
    if (changeAvatarBtn) {
        changeAvatarBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            avatarInput.click();
        });
    }
    if (avatarInput) {
        avatarInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    modalAvatarPreview.src = e.target.result;
                    const mainAvatar = document.querySelector('.profile-avatar');
                    if (mainAvatar) mainAvatar.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // =============================
    // Tabs
    // =============================
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-item');
        const params = new URLSearchParams(window.location.search);
        const current = params.get('tab') || 'videos';
        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === current);
            tab.addEventListener('click', function() {
                const tabName = this.dataset.tab;
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url);
                window.location.reload();
            });
        });
    });

    // =============================
    // ESC to close modal
    // =============================
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal?.style.display === 'flex') modal.style.display = 'none';
    });

    // =============================
    // Follow button functionality
    // =============================
    document.addEventListener('DOMContentLoaded', function() {
        const followBtn = document.getElementById('followBtn');
        if (followBtn) {
            const userId = followBtn.dataset.userId;
            
            followBtn.addEventListener('click', function() {
                const originalText = followBtn.innerHTML;
                const originalClass = followBtn.className;
                
                followBtn.disabled = true;
                followBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                
                fetch(`/user/${userId}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update button text and style
                        if (data.following) {
                            followBtn.innerHTML = '<i class="fas fa-check"></i> Following';
                            followBtn.className = 'btn btn-secondary';
                        } else {
                            followBtn.innerHTML = '<i class="fas fa-plus"></i> Follow';
                            followBtn.className = 'btn btn-primary';
                        }
                        
                        // Update followers count
                        const followerCount = document.getElementById('followersCount');
                        if (followerCount) {
                            followerCount.textContent = data.followers_count;
                        }
                        
                        // Show success message
                        showToast(data.message, 'success');
                    } else {
                        throw new Error(data.message || 'Follow action failed');
                    }
                })
                .catch(err => {
                    console.error('Follow error:', err);
                    followBtn.innerHTML = originalText;
                    followBtn.className = originalClass;
                    showToast('Failed to update follow status', 'error');
                })
                .finally(() => {
                    followBtn.disabled = false;
                });
            });
        }
    });

    // =============================
    // Profile form submission
    // =============================
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Update error:', error);
                    showToast(error.message, 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
</script>

</body>
</html>