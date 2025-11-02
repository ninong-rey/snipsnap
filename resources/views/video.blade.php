<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnipSnap Video</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff; color: #000; height: 100vh; overflow: hidden; display: flex; justify-content: center; align-items: center; }
        .container { display: flex; width: 100%; max-width: 1200px; height: 100vh; max-height: 900px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 0 30px rgba(0,0,0,0.1); }
        .video-section { flex: 1.5; display: flex; justify-content: center; align-items: center; background: #000; position: relative; overflow: hidden; }
        video { width: 100%; height: 100%; object-fit: cover; outline: none; }
        .caption { position: absolute; bottom: 120px; left: 20px; color: #fff; font-size: 16px; max-width: 80%; text-shadow: 0 2px 10px rgba(0,0,0,0.8); line-height: 1.4; }
        .user-info-video { position: absolute; bottom: 80px; left: 20px; display: flex; align-items: center; gap: 10px; }
        .user-info-video img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #fff; object-fit: cover; }
        .user-info-video strong { font-size: 16px; color: #fff; }
        .follow-btn { background: #ff0050; color: #fff; border: none; border-radius: 4px; padding: 4px 12px; font-size: 12px; font-weight: 600; cursor: pointer; margin-left: 10px; }
        .sound-info { position: absolute; bottom: 50px; left: 20px; display: flex; align-items: center; gap: 8px; font-size: 14px; color: #fff; }
        .sound-info i { color: #fff; }
        
        /* Three dots menu styles */
        .comment-menu, .reply-menu {
            position: relative;
            display: none;
        }

        .comment:hover .comment-menu,
        .reply:hover .reply-menu {
            display: block;
        }

        .menu-dots {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .menu-dots:hover {
            background: rgba(0,0,0,0.1);
        }

        .menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 100;
            min-width: 120px;
            display: none;
        }

        .menu-dropdown.show {
            display: block;
        }

        .menu-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            color: #666;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item:hover {
            background: #f8f8f8;
            color: #000;
        }

        .menu-item.delete {
            color: #ff0050;
        }

        /* Time stamp styles */
        .time-stamp {
            font-size: 11px;
            color: #999;
            margin-top: 2px;
        }

        /* Reply to reply functionality */
        .reply .comment-actions {
            margin-top: 5px;
        }

        .reply .reply-form {
            margin-top: 8px;
        }
        
        .heart {
            position: absolute;
            color: #ff0050;
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
        
        .sidebar {
            flex: 1; 
            background: #fff; 
            display: flex; 
            flex-direction: column; 
            padding: 20px; 
            overflow-y: auto; 
            border-left: 1px solid #eee; 
            position: relative;
            height: 100vh;
        }
        
        .sidebar-buttons {
            display: flex;
            flex-direction: row;
            margin-top: 20px;
            margin-bottom: -45px;
            margin-right: 66%;
            justify-content: flex-end;
            padding-right: 20px;
            color: white;
        }
        
        .sidebar > div:first-child {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 80px;
        }

        .comment-form {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
            z-index: 100;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            margin-top: auto;
        }
        
        .comments {
            flex: 1;
            overflow-y: auto;
            margin-top: 20px;
            padding-right: 10px;
            display: none;
        }

        .comments.active {
            display: block;
        }

        .action-btn { 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            background: none; 
            border: none; 
            color: #000; 
            cursor: pointer; 
            font-size: 12px;  
            min-width: 45px;
        }
        
        .action-btn .icon-wrapper { 
            width: 10px; 
            height: 10px; 
            border-radius: 50%; 
            background: rgba(0,0,0,0.05); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 5px; 
            flex-direction: row;
        } 
        
        .action-btn i { font-size: 24px; }
        
        .action-btn .count { 
            margin-top: 5px;
            font-size: 10px; 
            font-weight: 600; 
            color: red;
        } 
        
        .like-btn .icon-wrapper { background: rgba(255,20,147,0.1); }
        .liked i { color: #ff0050; }
        
        .share-btns { display: flex; gap: 10px; margin-top: 20px; margin-left:40%; }
        .share-icon { width: 20px; height: 20px; cursor: pointer; border-radius: 4px; transition: transform 0.2s; }
        .share-icon:hover { transform: scale(1.1); }
        
        .comments h3 { margin-bottom: 15px; font-size: 18px; }
        .comment { 
            background: #f8f8f8; 
            border-radius: 12px; 
            padding: 12px; 
            margin-bottom: 12px; 
            transition: background 0.2s; 
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .comment-content { flex: 1; }
        .comment:hover { background: #f0f0f0; }
        .comment strong { display: block; color: #000; font-size: 14px; margin-bottom: 5px; }
        .comment p { margin: 0; font-size: 14px; line-height: 1.4; color: #333; }
        .comment-actions { display: flex; gap: 15px; margin-top: 8px; font-size: 12px; color: #666; }
        .comment-actions span { cursor: pointer; transition: color 0.2s; }
        .comment-actions span:hover { color: #000; }
        
        /* Comment heart reaction - moved to right side */
        .comment-heart-reaction {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 15px;
            min-width: 40px;
        }
        .comment-heart-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .comment-heart-btn:hover {
            background: rgba(255, 0, 80, 0.1);
        }
        .comment-heart-btn i {
            font-size: 16px;
            color: #666;
            transition: color 0.2s;
        }
        .comment-heart-btn.liked i {
            color: #ff0050;
        }
        .comment-heart-count {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
            font-weight: 600;
        }
        
        .video-thumbnail {
            aspect-ratio: 9/16;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        
        .video-thumbnail video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-thumbnail-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .video-thumbnail:hover .video-thumbnail-overlay {
            opacity: 1;
        }

        .play-icon-small {
            color: white;
            font-size: 24px;
        }
        
        .reply-form { display: none; margin-top: 10px; width: 100%; }
        .comment-form textarea { flex: 1; background: #f8f8f8; border: 1px solid #ddd; border-radius: 20px; padding: 12px 15px; resize: none; font-size: 14px; color: #000; outline: none; }
        .comment-form textarea::placeholder { color: #999; }
        .comment-form button { background: #ff0050; color: #fff; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .comment-form button:hover { background: #e00045; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background-color: #ccc; border-radius: 4px; }
        .search-container { display: flex; align-items: center; background: #f8f8f8; border-radius: 20px; padding: 8px 15px; margin-bottom: 20px; }
        .search-container i { color: #999; margin-right: 10px; }
        .search-container input { background: transparent; border: none; color: #000; width: 100%; outline: none; font-size: 14px; }
        .search-container input::placeholder { color: #999; }
        .ai-label { display: flex; align-items: center; gap: 8px; color: #666; font-size: 12px; margin-top: 30px; }
        .ai-label i { color: #00f2ea; }
        .link-container { display: flex; align-items: center; background: #f8f8f8; border-radius: 8px; padding: 10px 15px; margin-top: 15px; font-size: 14px; }
        .link-container i { margin-right: 8px; color: #00f2ea; }
        .link-text { color: #666; flex-grow: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .copy-btn { color: #00f2ea; font-weight: 600; cursor: pointer; font-size: 12px; }
        .promote-section { background: #f8f8f8; border-radius: 12px; padding: 15px; margin-top: 15px; }
        .promote-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .promote-title { font-size: 16px; font-weight: 600; }
        .promote-btn { background: #00f2ea; color: #000; border: none; border-radius: 4px; padding: 6px 12px; font-weight: 600; cursor: pointer; font-size: 12px; }
        .promote-text { color: #666; font-size: 12px; }
        .creator-videos { margin-top: 20px; display: none; }
        .creator-videos.active { display: block; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .section-title { font-size: 16px; font-weight: 600; }
        .see-all { color: #666; font-size: 12px; cursor: pointer; }
        .video-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; }
        .video-info { margin-bottom: 15px; }
        .video-info h2 { font-size: 22px; margin-bottom: 5px; }
        .video-details { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stats-container { display: flex; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 20px; }
        .stat-item { flex: 1; text-align: center; padding: 10px 0; }
        .stat-count { font-size: 18px; font-weight: 700; margin-bottom: 5px; }
        .stat-label { font-size: 14px; color: #666; }
        .reaction-counts { display: flex; gap: 15px; margin: 10px 0; font-size: 14px; color: #666; }
        .reaction-count { display: flex; align-items: center; gap: 5px; }
        .toggle-buttons { display: flex; gap: 10px; margin-top: 15px; }
        .toggle-btn { background: #f8f8f8; border: 1px solid #ddd; border-radius: 20px; padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .toggle-btn.active { background: #ff0050; color: #fff; border-color: #ff0050; }

        /* Reply system styles */
        .replies-section {
            margin-top: 10px;
            margin-left: 20px;
            border-left: 2px solid #e0e0e0;
            padding-left: 15px;
        }
        .reply-toggle {
            color: #666;
            font-size: 12px;
            cursor: pointer;
            margin-top: 5px;
            font-weight: 600;
            display: inline-block;
        }
        .reply-toggle:hover {
            color: #000;
        }
        .reply {
            background: #f8f8f8;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 8px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-left: 3px solid #ff0050;
        }
        
        .reply-content { 
            flex: 1; 
        }

        .reply strong { 
            display: block; 
            color: #000; 
            font-size: 14px; 
            margin-bottom: 3px; 
        }

        .reply p { 
            margin: 0; 
            font-size: 14px; 
            line-height: 1.4; 
            color: #333; 
        }
        
        /* Highlight animation for new comments */
        @keyframes highlight {
            0% { background-color: rgba(255, 215, 0, 0.3); }
            100% { background-color: #f8f8f8; }
        }
        
        .comment-highlight {
            animation: highlight 2s ease-in-out;
        }
        
        /* Video Controls */
        .video-controls {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 20;
        }

        .close-btn, .nav-btn {
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }

        .close-btn:hover, .nav-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }

        /* Play/Pause Animation */
        .play-pause-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 15;
            pointer-events: none;
        }

        .play-pause-overlay.show {
            opacity: 1;
        }

        .play-pause-icon {
            font-size: 80px;
            color: white;
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Video Section -->
    <div class="video-section" ondblclick="doubleTapLike(event)">
        <!-- Video Controls -->
        <div class="video-controls">
            <button class="close-btn" onclick="goToProfile()">
                <i class="fas fa-times"></i>
            </button>
            <div class="nav-buttons">
                <button class="nav-btn" onclick="navigateVideo('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-btn" onclick="navigateVideo('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Play/Pause Overlay -->
        <div class="play-pause-overlay" id="playPauseOverlay">
            <i class="fas fa-play play-pause-icon" id="playPauseIcon"></i>
        </div>
        
        <video id="mainVideo" controls autoplay muted playsinline loop onclick="togglePlayPause()">
    <source src="{{ $video->url ?? 'https://assets.mixkit.co/videos/preview/mixkit-tree-with-yellow-flowers-1173-large.mp4' }}" type="video/mp4">
    Your browser does not support the video tag.
</video>
        
        <div class="caption">{{ $video->caption ?? 'Check out this amazing view! 🌄 #nature #travel #beautiful' }}</div>
        <div class="user-info-video">
            <img src="{{ $video->user->avatar ? asset('storage/' . $video->user->avatar) : 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' }}" alt="Profile">
            <strong>{{ $video->user->username ?? $video->user->name }}</strong>

            @auth
                @if(Auth::id() != $video->user->id)
                    <button class="follow-btn">Follow</button>
                @endif
            @else
                <a href="{{ route('login.view') }}" class="follow-btn">Follow</a>
            @endauth
        </div>
        <div class="sound-info">
            <i class="fas fa-music"></i>
            <span>original sound - {{ $video->user->username ?? $video->user->name }}</span>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Find related content">
            </div>
        
            <div class="video-info">
                <h2>{{ $video->user->username ?? $video->user->name }}</h2>
                <div class="video-details">{{ $video->created_at->diffForHumans() ?? 'Recently' }}</div>

                <div class="sidebar-buttons">
                    @auth
                        <button class="action-btn like-btn" onclick="likeVideo(this)">
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                            <span class="count" id="like-count">{{ $video->likes_count ?? 0 }}</span>
                        </button>
                    @else
                        <a href="{{ route('login.view') }}" class="action-btn like-btn">
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                            <span class="count" id="like-count">{{ $video->likes_count ?? 0 }}</span>
                        </a>
                    @endauth

                    <button class="action-btn comment-btn" onclick="toggleSection('comments')">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-comment"></i>
                        </div>
                        <span class="count" id="comment-count">{{ $video->comments_count ?? 0 }}</span>
                    </button>

                    <button class="action-btn share-btn" onclick="shareVideo()">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-share"></i>
                        </div>
                        <span class="count" id="share-count">{{ $video->shares_count ?? 0 }}</span>
                    </button>
                </div>
            
                <div class="share-btns">
                    <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" class="share-icon" alt="FB"></a>
                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111646.png" class="share-icon" alt="TG"></a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode(url()->current()) }}" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" class="share-icon" alt="WA"></a>
                    <a href="https://www.tiktok.com/upload?url={{ urlencode(url()->current()) }}" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/3046/3046126.png" class="share-icon" alt="TT"></a>
                </div>

                <div class="ai-label">
                    <i class="fas fa-robot"></i>
                    <span>CapCut - Editing made easy Creator labeled as AI-generated</span>
                </div>

                <div class="link-container">
                    <i class="fas fa-link"></i>
                    <div class="link-text">{{ url()->current() }}</div>
                    <div class="copy-btn" onclick="copyLink()">Copy link</div>
                </div>
            </div>

            <div class="toggle-buttons">
                <button class="toggle-btn active" data-target="comments">Comments</button>
                <button class="toggle-btn" data-target="creator-videos">Creator Videos</button>
            </div>

            <div class="promote-section">
                <div class="promote-header">
                    <div class="promote-title">{{ $video->user->username ?? $video->user->name }}</div>
                    <button class="promote-btn">Promote video</button>
                </div>
                <div class="promote-text">View Analytics</div>
            </div>

            <div class="comments active" id="comments-container">
                <h3>Comments</h3>
                @forelse($video->comments->where('parent_id', null) as $comment)
                    <div class="comment" data-id="{{ $comment->id }}">
                        <div class="comment-content">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <strong>{{ $comment->user->username ?? $comment->user->name }}</strong>
                                    <div class="time-stamp">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="comment-menu">
                                    <button class="menu-dots" onclick="toggleMenu(this)">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="menu-dropdown">
                                        @if(Auth::id() == $comment->user_id || Auth::id() == $video->user_id)
                                            <div class="menu-item delete" onclick="deleteComment({{ $comment->id }})">Delete</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p>{{ $comment->content }}</p>
                            <div class="comment-actions">
                                <span class="reply-btn" onclick="toggleReplyForm(this)">Reply</span>
                                @if($comment->replies_count > 0)
                                    <span class="reply-toggle" onclick="toggleReplies(this, {{ $comment->id }})">
                                        View {{ $comment->replies_count }} {{ $comment->replies_count == 1 ? 'reply' : 'replies' }}
                                    </span>
                                @endif
                            </div>
                            <form class="reply-form" onsubmit="submitReply(event, {{ $comment->id }})">
                                @csrf
                                <textarea placeholder="Add reply... @mention, emoji supported" rows="1" required></textarea>
                                <button type="submit">Post</button>
                            </form>
                            <div class="replies-section" id="replies-{{ $comment->id }}" style="display: none;">
                                @foreach($comment->replies as $reply)
                                    <div class="reply" data-id="{{ $reply->id }}">
                                        <div class="reply-content">
                                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                                <div>
                                                    <strong>{{ $reply->user->username ?? $reply->user->name }}</strong>
                                                    <div class="time-stamp">{{ $reply->created_at->diffForHumans() }}</div>
                                                </div>
                                                <div class="reply-menu">
                                                    <button class="menu-dots" onclick="toggleMenu(this)">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="menu-dropdown">
                                                        @if(Auth::id() == $reply->user_id || Auth::id() == $video->user_id)
                                                            <div class="menu-item delete" onclick="deleteReply({{ $reply->id }})">Delete</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <p>{{ $reply->content }}</p>
                                            <div class="comment-actions">
                                                <span class="reply-btn" onclick="toggleReplyForm(this)">Reply</span>
                                            </div>
                                            <form class="reply-form" onsubmit="submitReplyToReply(event, {{ $reply->id }}, {{ $comment->id }})">
                                                @csrf
                                                <textarea placeholder="Add reply... @mention, emoji supported" rows="1" required></textarea>
                                                <button type="submit">Post</button>
                                            </form>
                                        </div>
                                        <div class="comment-heart-reaction">
                                            <button class="comment-heart-btn" onclick="likeComment(this, {{ $reply->id }})">
                                                <i class="fa-{{ $reply->is_liked ? 'solid' : 'regular' }} fa-heart"></i>
                                            </button>
                                            <span class="comment-heart-count">{{ $reply->likes ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="comment-heart-reaction">
                            <button class="comment-heart-btn" onclick="likeComment(this, {{ $comment->id }})">
                                <i class="fa-{{ $comment->is_liked ? 'solid' : 'regular' }} fa-heart"></i>
                            </button>
                            <span class="comment-heart-count">{{ $comment->likes ?? 0 }}</span>
                        </div>
                    </div>
                @empty
                    <p style="color: #777; text-align: center; padding: 20px;">No comments yet. Be the first!</p>
                @endforelse
            </div>

            <div class="creator-videos" id="creator-videos-container">
                <div class="section-header">
                    <div class="section-title">Creator videos</div>
                    <div class="see-all" onclick="seeAllVideos()">See all</div>
                </div>
                <div class="video-grid">
                    @php
                        $creatorVideos = $video->user->videos->where('id', '!=', $video->id);
                    @endphp
                    @forelse($creatorVideos->take(6) as $creatorVideo)
                        <div class="video-thumbnail" onclick="playCreatorVideo({{ $creatorVideo->id }})">
                            <video muted preload="metadata">
                               <source src="{{ $creatorVideo->url ?? 'https://assets.mixkit.co/videos/preview/mixkit-tree-with-yellow-flowers-1173-large.mp4' }}" type="video/mp4">
                                </video>
                            <div class="video-thumbnail-overlay">
                                <i class="fas fa-play play-icon-small"></i>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; color: #999; padding: 20px;">
                            <p>No other videos from this creator</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Comment Form -->
        @auth
        <form id="main-comment-form" class="comment-form" onsubmit="submitComment(event)">
            @csrf
            <input type="hidden" name="video_id" value="{{ $video->id }}">
            <textarea name="content" rows="2" placeholder="Add a comment... @mention, emoji supported" required></textarea>
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
        @else
        <a href="{{ route('login.view') }}" class="comment-form" style="text-decoration: none;">
            <textarea placeholder="Add a comment... Login to comment" disabled style="width: 100%;"></textarea>
        </a>
        @endauth
    </div>
</div>

<script>
    // Global variables
    let liked = localStorage.getItem('videoLiked{{ $video->id }}') === 'true' || false;
    let likeCount = parseInt(localStorage.getItem('likeCount{{ $video->id }}')) || {{ $video->likes_count ?? 0 }};
    let commentCount = parseInt(localStorage.getItem('commentCount{{ $video->id }}')) || {{ $video->comments_count ?? 0 }};
    let shareCount = parseInt(localStorage.getItem('shareCount{{ $video->id }}')) || {{ $video->shares_count ?? 0 }};
    
    // Video navigation variables
    let currentVideoIndex = 0;
    let userVideos = @json($video->user->videos);

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial counts
        document.getElementById('like-count').textContent = likeCount;
        document.getElementById('comment-count').textContent = commentCount;
        document.getElementById('share-count').textContent = shareCount;
        
        // Set like button state
        if (liked) {
            document.querySelector('.like-btn i').classList.add('liked');
        }
        
        // Initialize video navigation
        initializeVideoNavigation();
        
        // Set up toggle buttons
        setupToggleButtons();
        
        // Load saved like states
        loadLikeStates();
    });

    // Initialize video navigation
    function initializeVideoNavigation() {
        const currentVideoId = {{ $video->id }};
        currentVideoIndex = userVideos.findIndex(video => video.id == currentVideoId);
        if (currentVideoIndex === -1) currentVideoIndex = 0;
        
        console.log('Total videos:', userVideos.length);
        console.log('Current video index:', currentVideoIndex);
    }

    // Navigation function for next/previous
    function navigateVideo(direction) {
        if (userVideos.length <= 1) {
            alert('No other videos available');
            return;
        }
        
        if (direction === 'next') {
            currentVideoIndex = (currentVideoIndex + 1) % userVideos.length;
        } else if (direction === 'prev') {
            currentVideoIndex = (currentVideoIndex - 1 + userVideos.length) % userVideos.length;
        }
        
        console.log('Navigating to video index:', currentVideoIndex);
        loadVideo(userVideos[currentVideoIndex]);
    }

    // Load video function
    function loadVideo(videoData) {
        console.log('Loading video:', videoData);
        
        const videoElement = document.getElementById('mainVideo');
        const videoSource = videoElement.querySelector('source');
        
        // Update video source
        const videoUrl = videoData.url || 'https://assets.mixkit.co/videos/preview/mixkit-tree-with-yellow-flowers-1173-large.mp4';
        videoSource.src = videoUrl;
        
        // Update video element
        videoElement.load();
        
        // Update video info
        document.querySelector('.caption').textContent = videoData.caption || 'Check out this amazing view! 🌄 #nature #travel #beautiful';
        
        // Update user info
        const userInfo = document.querySelector('.user-info-video');
        const userImg = userInfo.querySelector('img');
        const userName = userInfo.querySelector('strong');
        
        userImg.src = videoData.user.avatar ? 'videoData.user.avatar : 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60';
        userName.textContent = videoData.user.username || videoData.user.name;
        
        // Update sound info
        document.querySelector('.sound-info span').textContent = 'original sound - ' + (videoData.user.username || videoData.user.name);
        
        // Reset counts for new video
        resetVideoCounts(videoData.id);
        
        // Play the video after a short delay
        setTimeout(() => {
            videoElement.play().catch(e => console.log('Autoplay prevented:', e));
        }, 100);
    }

    // Play creator video from thumbnail
    function playCreatorVideo(videoId) {
        console.log('Playing creator video:', videoId);
        const videoIndex = userVideos.findIndex(video => video.id == videoId);
        if (videoIndex !== -1) {
            currentVideoIndex = videoIndex;
            loadVideo(userVideos[videoIndex]);
            
            // Switch to comments section when playing a video
            toggleSection('comments');
        }
    }

    // See all videos function
    function seeAllVideos() {
        window.location.href = "{{ route('profile.show', $video->user->id) }}";
    }

    // Go to profile function
    function goToProfile() {
        window.location.href = "{{ route('profile.show', $video->user->id) }}";
    }

    // Reset counts for new video
    function resetVideoCounts(videoId) {
        // Reset like state
        liked = false;
        document.querySelector('.like-btn i').classList.remove('liked');
        
        // Reset counts
        likeCount = 0;
        commentCount = 0;
        shareCount = 0;
        
        document.getElementById('like-count').textContent = likeCount;
        document.getElementById('comment-count').textContent = commentCount;
        document.getElementById('share-count').textContent = shareCount;
    }

    // Create heart animation function
    function createHeart(x, y, container) {
        const heart = document.createElement('i');
        heart.className = 'fa-solid fa-heart heart active';
        heart.style.left = `${x}px`;
        heart.style.top = `${y}px`;
        heart.style.transform = 'translate(-50%, -50%)';
        container.appendChild(heart);
        setTimeout(() => heart.remove(), 1400);
    }
    
    function toggleSection(section) {
        console.log('Toggling to:', section);
        
        const commentsSection = document.getElementById('comments-container');
        const creatorVideosSection = document.getElementById('creator-videos-container');
        const commentToggle = document.querySelector('[data-target="comments"]');
        const creatorToggle = document.querySelector('[data-target="creator-videos"]');
        const commentForm = document.getElementById('main-comment-form');
        
        // Remove all active classes first
        commentsSection.classList.remove('active');
        creatorVideosSection.classList.remove('active');
        commentToggle.classList.remove('active');
        creatorToggle.classList.remove('active');
        
        // Add active classes to selected section
        if (section === 'comments') {
            commentsSection.classList.add('active');
            commentToggle.classList.add('active');
            // Show comment form when comments section is active
            if (commentForm) {
                commentForm.style.display = 'flex';
            }
        } else if (section === 'creator-videos') {
            creatorVideosSection.classList.add('active');
            creatorToggle.classList.add('active');
            // Hide comment form when creator videos section is active
            if (commentForm) {
                commentForm.style.display = 'none';
            }
        }
    }

    // Setup toggle buttons
    function setupToggleButtons() {
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                toggleSection(target);
            });
        });
    }
    
    // Double-tap to like with heart animation
    function doubleTapLike(event) {
        const videoSection = event.currentTarget;
        const rect = videoSection.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
        createHeart(x, y, videoSection);
        
        if(!liked) {
            incrementLike();
            liked = true;
            localStorage.setItem('videoLiked{{ $video->id }}', 'true');
            document.querySelector('.like-btn i').classList.add('liked');
        }
    }
    
    // Increment like count
    function incrementLike() {
        likeCount++;
        document.getElementById('like-count').textContent = likeCount;
        localStorage.setItem('likeCount{{ $video->id }}', likeCount);
        
        // Send AJAX request to update like in database
        fetch('{{ route("video.like", $video->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }
    
    // Clicking sidebar like button toggles like/unlike
    function likeVideo(btn) {
        const icon = btn.querySelector('i');
        if(!liked) {
            likeCount++;
            liked = true;
            icon.classList.add('liked');
            
            // Send AJAX request to like video
            fetch('{{ route("video.like", $video->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        } else {
            likeCount--;
            liked = false;
            icon.classList.remove('liked');
            
            // Send AJAX request to unlike video
            fetch('{{ route("video.unlike", $video->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }
        document.getElementById('like-count').textContent = likeCount;
        localStorage.setItem('likeCount{{ $video->id }}', likeCount);
        localStorage.setItem('videoLiked{{ $video->id }}', liked);
    }
    
    // Share video function
    function shareVideo() {
        shareCount++;
        document.getElementById('share-count').textContent = shareCount;
        localStorage.setItem('shareCount{{ $video->id }}', shareCount);
        
        // Send AJAX request to increment share count
        fetch('{{ route("video.share", $video->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        // Show a simple share dialog
        alert('Share this video with your friends!');
    }
    
    // Copy link functionality
    function copyLink() {
        const linkText = document.querySelector('.link-text').textContent;
        navigator.clipboard.writeText(linkText).then(function() {
            const copyBtn = document.querySelector('.copy-btn');
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(() => {
                copyBtn.textContent = originalText;
            }, 2000);
        });
    }
    
    // Comment reply and heart functionality
    function toggleReplyForm(el) {
        const form = el.closest('.comment, .reply').querySelector('.reply-form');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
    
    // Like comment with heart button
    function likeComment(btn, commentId) {
        const icon = btn.querySelector('i');
        const countSpan = btn.parentElement.querySelector('.comment-heart-count');
        let currentLikes = parseInt(countSpan.textContent) || 0;
        const isLiked = icon.classList.contains('fa-solid');
        
        if (!isLiked) {
            currentLikes++;
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            btn.classList.add('liked');
            icon.style.color = '#ff0050';
        } else {
            currentLikes--;
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            btn.classList.remove('liked');
            icon.style.color = '#666';
        }
        
        countSpan.textContent = currentLikes;
        
        // Save to localStorage with proper key
        saveLikeState(commentId, !isLiked, currentLikes);
        
        // Send AJAX request to like/unlike comment
        fetch('/comment/' + commentId + '/like', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).catch(error => {
            console.error('Error liking comment:', error);
        });
    }

    // Load like states from localStorage
    function loadLikeStates() {
        const videoId = {{ $video->id }};
        const likeData = JSON.parse(localStorage.getItem(`commentLikes_${videoId}`) || '{}');
        
        // Load for main comments
        document.querySelectorAll('.comment[data-id]').forEach(commentElement => {
            const commentId = commentElement.getAttribute('data-id');
            const data = likeData[commentId];
            
            if (data) {
                const heartBtn = commentElement.querySelector('.comment-heart-btn');
                const countSpan = commentElement.querySelector('.comment-heart-count');
                
                if (heartBtn && countSpan) {
                    const icon = heartBtn.querySelector('i');
                    if (data.isLiked) {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                        heartBtn.classList.add('liked');
                        icon.style.color = '#ff0050';
                    }
                    countSpan.textContent = data.likeCount || 0;
                }
            }
        });
        
        // Load for replies
        document.querySelectorAll('.reply[data-id]').forEach(replyElement => {
            const replyId = replyElement.getAttribute('data-id');
            const data = likeData[replyId];
            
            if (data) {
                const heartBtn = replyElement.querySelector('.comment-heart-btn');
                const countSpan = replyElement.querySelector('.comment-heart-count');
                
                if (heartBtn && countSpan) {
                    const icon = heartBtn.querySelector('i');
                    if (data.isLiked) {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                        heartBtn.classList.add('liked');
                        icon.style.color = '#ff0050';
                    }
                    countSpan.textContent = data.likeCount || 0;
                }
            }
        });
    }

    // Save like state to localStorage
    function saveLikeState(commentId, isLiked, likeCount) {
        const videoId = {{ $video->id }};
        const likeData = JSON.parse(localStorage.getItem(`commentLikes_${videoId}`) || '{}');
        likeData[commentId] = { 
            isLiked: isLiked, 
            likeCount: likeCount 
        };
        localStorage.setItem(`commentLikes_${videoId}`, JSON.stringify(likeData));
    }

    // Toggle replies visibility
    function toggleReplies(el, commentId) {
        const repliesSection = document.getElementById('replies-' + commentId);
        const isVisible = repliesSection.style.display === 'block';
        
        if (!isVisible) {
            repliesSection.style.display = 'block';
            el.textContent = 'Hide replies';
        } else {
            repliesSection.style.display = 'none';
            el.textContent = el.textContent.replace('Hide', 'View');
        }
    }

    // Submit new comment
    function submitComment(event) {
        event.preventDefault();
        
        const form = event.target;
        const textarea = form.querySelector('textarea');
        const content = textarea.value.trim();
        
        if(!content) {
            alert('Please enter a comment');
            return;
        }

        // Show loading state
        const submitBtn = form.querySelector('button');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        submitBtn.disabled = true;

        // Create form data manually to ensure correct data
        const formData = new FormData();
        formData.append('video_id', '{{ $video->id }}');
        formData.append('content', content);
        formData.append('_token', '{{ csrf_token() }}');

        // Send AJAX request to submit comment
        fetch('{{ route("comment.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update comment count
                commentCount++;
                document.getElementById('comment-count').textContent = commentCount;
                localStorage.setItem('commentCount{{ $video->id }}', commentCount);
                
                // Show success state
                submitBtn.innerHTML = '<i class="fas fa-check"></i>';
                
                // Add comment to DOM without reloading
                addCommentToDOM(data.comment);
                
                // Clear form
                textarea.value = '';
                
                // Restore button after 1 second
                setTimeout(() => {
                    submitBtn.innerHTML = originalHtml;
                    submitBtn.disabled = false;
                }, 1000);
                
            } else {
                const errorMsg = data.message || 'Unknown error occurred';
                alert('Failed to post comment: ' + errorMsg);
                
                // Restore button state on error
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error posting comment. Please try again.');
            
            // Restore button state on error
            submitBtn.innerHTML = originalHtml;
            submitBtn.disabled = false;
        });
    }

    // Function to add comment to DOM without reloading
    function addCommentToDOM(commentData) {
        const commentsContainer = document.getElementById('comments-container');
        
        // Create new comment element
        const commentDiv = document.createElement('div');
        commentDiv.className = 'comment comment-highlight';
        commentDiv.setAttribute('data-id', commentData.id);
        
        commentDiv.innerHTML = `
            <div class="comment-content">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <strong>${commentData.user ? (commentData.user.username || commentData.user.name) : 'You'}</strong>
                        <div class="time-stamp">just now</div>
                    </div>
                    <div class="comment-menu">
                        <button class="menu-dots" onclick="toggleMenu(this)">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="menu-dropdown">
                            <div class="menu-item delete" onclick="deleteComment(${commentData.id})">Delete</div>
                        </div>
                    </div>
                </div>
                <p>${commentData.content}</p>
                <div class="comment-actions">
                    <span class="reply-btn" onclick="toggleReplyForm(this)">Reply</span>
                </div>
                <form class="reply-form" onsubmit="submitReply(event, ${commentData.id})">
                    <textarea placeholder="Add reply... @mention, emoji supported" rows="1" required></textarea>
                    <button type="submit">Post</button>
                </form>
                <div class="replies-section" id="replies-${commentData.id}" style="display: none;">
                    <!-- Replies will be loaded here -->
                </div>
            </div>
            <div class="comment-heart-reaction">
                <button class="comment-heart-btn" onclick="likeComment(this, ${commentData.id})">
                    <i class="fa-regular fa-heart"></i>
                </button>
                <span class="comment-heart-count">0</span>
            </div>
        `;
        
        // Add the new comment at the top of the comments list
        const firstComment = commentsContainer.querySelector('.comment');
        if (firstComment) {
            commentsContainer.insertBefore(commentDiv, firstComment);
        } else {
            // If no comments exist, remove the "no comments" message and add the new one
            const noCommentsMsg = commentsContainer.querySelector('p');
            if (noCommentsMsg) {
                noCommentsMsg.remove();
            }
            commentsContainer.appendChild(commentDiv);
        }
    }
    
    // Submit reply
    function submitReply(event, commentId) {
        event.preventDefault();
        const form = event.target;
        const textarea = form.querySelector('textarea');
        const content = textarea.value.trim();
        
        if(!content) {
            alert('Please enter a reply');
            return;
        }
        
        // Create form data for reply
        const formData = new FormData();
        formData.append('video_id', '{{ $video->id }}');
        formData.append('content', content);
        formData.append('parent_id', commentId);
        formData.append('_token', '{{ csrf_token() }}');

        // Send AJAX request to submit reply
        fetch('{{ route("comment.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update comment count
                commentCount++;
                document.getElementById('comment-count').textContent = commentCount;
                localStorage.setItem('commentCount{{ $video->id }}', commentCount);
                
                // Add reply to DOM
                const parentComment = form.closest('.comment');
                const repliesSection = parentComment.querySelector('.replies-section');
                const replyToggle = parentComment.querySelector('.reply-toggle');
                
                // Create reply element
                const replyDiv = document.createElement('div');
                replyDiv.className = 'reply';
                replyDiv.setAttribute('data-id', data.comment.id);
                replyDiv.innerHTML = `
                    <div class="reply-content">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <strong>You</strong>
                                <div class="time-stamp">just now</div>
                            </div>
                            <div class="reply-menu">
                                <button class="menu-dots" onclick="toggleMenu(this)">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="menu-dropdown">
                                    <div class="menu-item delete" onclick="deleteReply(${data.comment.id})">Delete</div>
                                </div>
                            </div>
                        </div>
                        <p>${content}</p>
                        <div class="comment-actions">
                            <span class="reply-btn" onclick="toggleReplyForm(this)">Reply</span>
                        </div>
                        <form class="reply-form" onsubmit="submitReplyToReply(event, ${data.comment.id}, ${commentId})">
                            <textarea placeholder="Add reply... @mention, emoji supported" rows="1" required></textarea>
                            <button type="submit">Post</button>
                        </form>
                    </div>
                    <div class="comment-heart-reaction">
                        <button class="comment-heart-btn" onclick="likeComment(this, ${data.comment.id})">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        <span class="comment-heart-count">0</span>
                    </div>
                `;
                
                // Show replies section if hidden
                repliesSection.style.display = 'block';
                repliesSection.appendChild(replyDiv);
                
                // Update reply toggle text
                if (replyToggle) {
                    const currentCount = parseInt(replyToggle.textContent.match(/\d+/)[0]) || 0;
                    replyToggle.textContent = `Hide ${currentCount + 1} ${currentCount + 1 === 1 ? 'reply' : 'replies'}`;
                } else {
                    // Create reply toggle if it doesn't exist
                    const newReplyToggle = document.createElement('span');
                    newReplyToggle.className = 'reply-toggle';
                    newReplyToggle.textContent = 'Hide 1 reply';
                    newReplyToggle.onclick = function() { toggleReplies(this, commentId); };
                    parentComment.querySelector('.comment-actions').appendChild(newReplyToggle);
                }
                
                // Clear form and hide it
                textarea.value = '';
                form.style.display = 'none';
                
            } else {
                alert('Failed to post reply: ' + (data.message || 'Unknown error'));
            }
        }).catch(error => {
            console.error('Error posting reply:', error);
            alert('Error posting reply. Please try again.');
        });
    }

    // Toggle three dots menu
    function toggleMenu(button) {
        const dropdown = button.nextElementSibling;
        dropdown.classList.toggle('show');
        
        // Close other dropdowns
        document.querySelectorAll('.menu-dropdown.show').forEach(otherDropdown => {
            if (otherDropdown !== dropdown) {
                otherDropdown.classList.remove('show');
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.comment-menu') && !event.target.closest('.reply-menu')) {
            document.querySelectorAll('.menu-dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Delete comment
    function deleteComment(commentId) {
        if (confirm('Are you sure you want to delete this comment?')) {
            fetch('/comment/' + commentId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`.comment[data-id="${commentId}"]`).remove();
                    commentCount--;
                    document.getElementById('comment-count').textContent = commentCount;
                    localStorage.setItem('commentCount{{ $video->id }}', commentCount);
                } else {
                    alert('Failed to delete comment');
                }
            }).catch(error => {
                console.error('Error deleting comment:', error);
                alert('Error deleting comment');
            });
        }
    }

    // Delete reply
    function deleteReply(replyId) {
        if (confirm('Are you sure you want to delete this reply?')) {
            fetch('/comment/' + replyId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`.reply[data-id="${replyId}"]`).remove();
                    commentCount--;
                    document.getElementById('comment-count').textContent = commentCount;
                    localStorage.setItem('commentCount{{ $video->id }}', commentCount);
                } else {
                    alert('Failed to delete reply');
                }
            }).catch(error => {
                console.error('Error deleting reply:', error);
                alert('Error deleting reply');
            });
        }
    }

    // Submit reply to a reply (nested replies)
    function submitReplyToReply(event, parentReplyId, mainCommentId) {
        event.preventDefault();
        const form = event.target;
        const textarea = form.querySelector('textarea');
        const content = textarea.value.trim();
        
        if(!content) {
            alert('Please enter a reply');
            return;
        }
        
        const formData = new FormData();
        formData.append('video_id', '{{ $video->id }}');
        formData.append('content', content);
        formData.append('parent_id', parentReplyId);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("comment.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        }).then(response => response.json())
        .then(data => {
            if(data.success) {
                commentCount++;
                document.getElementById('comment-count').textContent = commentCount;
                localStorage.setItem('commentCount{{ $video->id }}', commentCount);
                
                const parentReply = form.closest('.reply');
                const repliesSection = document.getElementById('replies-' + mainCommentId);
                
                const replyDiv = document.createElement('div');
                replyDiv.className = 'reply';
                replyDiv.setAttribute('data-id', data.comment.id);
                replyDiv.innerHTML = `
                    <div class="reply-content">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <strong>You</strong>
                                <div class="time-stamp">just now</div>
                            </div>
                            <div class="reply-menu">
                                <button class="menu-dots" onclick="toggleMenu(this)">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="menu-dropdown">
                                    <div class="menu-item delete" onclick="deleteReply(${data.comment.id})">Delete</div>
                                </div>
                            </div>
                        </div>
                        <p>${content}</p>
                        <div class="comment-actions">
                            <span class="reply-btn" onclick="toggleReplyForm(this)">Reply</span>
                        </div>
                        <form class="reply-form" onsubmit="submitReplyToReply(event, ${data.comment.id}, ${mainCommentId})">
                            <textarea placeholder="Add reply... @mention, emoji supported" rows="1" required></textarea>
                            <button type="submit">Post</button>
                        </form>
                    </div>
                    <div class="comment-heart-reaction">
                        <button class="comment-heart-btn" onclick="likeComment(this, ${data.comment.id})">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        <span class="comment-heart-count">0</span>
                    </div>
                `;
                
                parentReply.parentNode.insertBefore(replyDiv, parentReply.nextSibling);
                textarea.value = '';
                form.style.display = 'none';
                
            } else {
                alert('Failed to post reply: ' + (data.message || 'Unknown error'));
            }
        }).catch(error => {
            console.error('Error posting reply:', error);
            alert('Error posting reply. Please try again.');
        });
    }

    // Play/Pause functionality
    function togglePlayPause() {
        const video = document.getElementById('mainVideo');
        const overlay = document.getElementById('playPauseOverlay');
        const icon = document.getElementById('playPauseIcon');
        
        if (video.paused) {
            video.play();
            overlay.classList.remove('show');
        } else {
            video.pause();
            icon.className = 'fas fa-play play-pause-icon';
            overlay.classList.add('show');
        }
    }

    // Show pause icon when video starts playing
    document.getElementById('mainVideo').addEventListener('play', function() {
        const overlay = document.getElementById('playPauseOverlay');
        const icon = document.getElementById('playPauseIcon');
        icon.className = 'fas fa-pause play-pause-icon';
        overlay.classList.add('show');
        setTimeout(() => {
            overlay.classList.remove('show');
        }, 500);
    });

    // Show play icon when video is paused
    document.getElementById('mainVideo').addEventListener('pause', function() {
        const overlay = document.getElementById('playPauseOverlay');
        const icon = document.getElementById('playPauseIcon');
        icon.className = 'fas fa-play play-pause-icon';
        overlay.classList.add('show');
    });
</script>
</body>
</html>