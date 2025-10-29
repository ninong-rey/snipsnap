<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use App\Notifications\TestNotification;
use App\Models\User;
use App\Models\Video;

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES - Add these at the top for testing
|--------------------------------------------------------------------------
*/

// Basic test routes
Route::get('/test-basic', function () {
    return 'Hello World - Basic test works!';
});

Route::get('/test-db', function () {
    try {
        $videos = Video::count();
        return "Database works! Videos count: " . $videos;
    } catch (\Exception $e) {
        return "Database error: " . $e->getMessage();
    }
});

Route::get('/test-upload-page', function () {
    return 'Simple upload test - no view';
});

// Add file_path column to videos table
Route::get('/add-file-path-column', function() {
    try {
        \DB::statement("ALTER TABLE videos ADD COLUMN file_path VARCHAR(255) NULL");
        return "file_path column added to videos table!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

/// PostgreSQL compatible debug
Route::get('/debug-videos-table', function() {
    try {
        // PostgreSQL way to get columns
        $columns = \DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'videos'");
        $columnNames = array_column($columns, 'column_name');
        
        echo "Columns in videos table:<br>";
        foreach ($columnNames as $column) {
            echo "- $column<br>";
        }
        
        echo "<br>Checking videos:<br>";
        $videos = \App\Models\Video::all();
        foreach ($videos as $video) {
            echo "Video ID: {$video->id} - file_path: '{$video->file_path}' - url: '{$video->url}'<br>";
        }
        
        return "Table debug complete";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/fix-videos-proper', function() {
    try {
        $videos = \App\Models\Video::all();
        $fixed = 0;
        
        foreach ($videos as $video) {
            // If file_path is empty but url has value
            if (empty($video->file_path) && !empty($video->url)) {
                // If url contains 'videos/', use it directly
                if (str_contains($video->url, 'videos/')) {
                    $video->file_path = $video->url;
                } else {
                    // Extract filename from url
                    $video->file_path = 'videos/' . basename($video->url);
                }
                $video->save();
                $fixed++;
                echo "Fixed video {$video->id}: {$video->file_path}<br>";
            }
        }
        
        return "Fixed $fixed videos!";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/web-debug', function() {
    try {
        // Test what WebController index does
        $videos = \App\Models\Video::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->get();
            
        return "WebController would return " . count($videos) . " videos";
        
    } catch (\Exception $e) {
        return "WebController error: " . $e->getMessage();
    }
});

// Old video fix route (keep for now)
Route::get('/fix-videos-simple', function() {
    try {
        // Check if file_path column exists
        $video = Video::first();
        if ($video && !isset($video->file_path)) {
            return "file_path column missing from videos table";
        }
        
        $videos = Video::all();
        $fixed = 0;
        
        foreach ($videos as $video) {
            if (empty($video->file_path) && !empty($video->url)) {
                // Simple fix - just use the url as file_path
                $video->file_path = $video->url;
                $video->save();
                $fixed++;
            }
        }
        
        return "Fixed $fixed videos!";
        
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/debug-login-process', function() {
    try {
        echo "<h3>Login Debug Info:</h3>";
        echo "Session ID: " . session()->getId() . "<br>";
        echo "CSRF Token: " . csrf_token() . "<br>";
        echo "Session Driver: " . config('session.driver') . "<br>";
        echo "Session Domain: " . config('session.domain') . "<br>";
        
        // Test database connection
        try {
            $userCount = \App\Models\User::count();
            echo "Users in database: " . $userCount . "<br>";
        } catch (\Exception $e) {
            echo "User database error: " . $e->getMessage() . "<br>";
        }
        
        return "Basic login debug complete";
        
    } catch (\Exception $e) {
        return "Login debug error: " . $e->getMessage();
    }
});

// Update session domain in config (temporary fix)
Route::get('/fix-session-domain', function() {
    config(['session.domain' => '.render.com']);
    return "Session domain updated to .render.com";
});
Route::get('/test-web-view', function() {
    try {
        // Get the same data as WebController
        $videos = \App\Models\Video::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->get();
            
        // Try to render the view with minimal data
        return view('web', ['videos' => $videos]);
        
    } catch (\Exception $e) {
        return "View error: " . $e->getMessage() . "<br><br>Stack trace: " . $e->getTraceAsString();
    }
});
Route::get('/test-simple-view', function() {
    try {
        $videos = \App\Models\Video::all();
        
        // Return a simple HTML to test if basic view works
        return "
            <html>
            <body>
                <h1>Simple Test</h1>
                <p>Videos count: " . count($videos) . "</p>
                <p>First video URL: " . ($videos->first()->url ?? 'None') . "</p>
            </body>
            </html>
        ";
        
    } catch (\Exception $e) {
        return "Simple view error: " . $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home - Keep your original home route
Route::get('/', [WebController::class, 'index'])->name('home');

Route::get('/test-notification', function () {
    $user = User::first(); // or Auth::user()
    $user->notify(new TestNotification("Hello from Render!"));
    return 'Notification sent!';
});

Route::get('/fake-notification', function () {
    return view('fake-notification');
});

// Serve uploaded videos safely
Route::get('/media/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
})->where('path', '.*');

// Video upload route (Ajax/Fetch)
Route::post('/upload', function(Request $request){
    $request->validate([
        'video' => 'required|mimes:mp4,mov,avi,webm|max:51200', // 50MB max
    ]);

    $path = $request->file('video')->store('videos', 'public');
    $url = Storage::url($path);

    return response()->json([
        'success' => true,
        'url' => $url
    ]);
})->name('upload.store');

// Upload page
Route::get('/upload', [VideoController::class, 'create'])->name('upload');
Route::post('/upload', [VideoController::class, 'store'])->name('upload.store');

// Authentication
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPerform'])->name('login.perform');

Route::get('/signup', [AuthController::class, 'signupView'])->name('signup.view');
Route::post('/signup', [AuthController::class, 'signupPerform'])->name('signup.perform');

Route::get('/reset', [AuthController::class, 'forgotPasswordView'])->name('password.request');
Route::post('/reset', [AuthController::class, 'otpRequest'])->name('otp.request');

Route::get('/reset-password', [AuthController::class, 'resetPasswordView'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'otpVerify'])->name('otp.verify');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.perform');

    // Main Feed
    Route::get('/web', [WebController::class, 'index'])->name('my-web');
    Route::get('/following', [FollowController::class, 'followingVideos'])->name('following.videos');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // User Profiles
    Route::get('/user/{username}', [WebController::class, 'showUserProfile'])->name('user.profile');
    Route::get('/users/{id}', [UserController::class, 'profile'])->name('users.profile');
    Route::get('/users/{id}/followers', [UserController::class, 'followers'])->name('users.followers');
    Route::get('/users/{id}/following', [UserController::class, 'following'])->name('users.following');
    Route::get('/users/{id}/videos', [UserController::class, 'videos'])->name('users.videos');
    Route::get('/users/{id}/liked-videos', [UserController::class, 'likedVideos'])->name('users.liked-videos');

    // Video Interactions
    Route::get('/video/{id}', [WebController::class, 'showVideo'])->name('video.show');
    Route::post('/video/{video}/like', [VideoController::class, 'like'])->name('video.like');
    Route::post('/video/{video}/unlike', [VideoController::class, 'unlike'])->name('video.unlike');
    Route::post('/video/{video}/share', [VideoController::class, 'share'])->name('video.share');

    // Comments
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    // Explore Users
    Route::get('/explore-users', [WebController::class, 'exploreUsers'])->name('explore.users');

    // Follow System
    Route::post('/user/{id}/follow', [FollowController::class, 'toggleFollow'])->name('follow.toggle');
    Route::get('/follow/status/{user}', [FollowController::class, 'followStatus'])->name('follow.status');

    // User suggestions and search
    Route::get('/users/suggestions', [UserController::class, 'suggestions'])->name('users.suggestions');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

    // Friends
    Route::get('/friends', [WebController::class, 'friends'])->name('friends');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-counts', [NotificationController::class, 'getUnreadCounts']);
    Route::get('/notifications/fetch-latest', [NotificationController::class, 'fetchLatest']);

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessagesController::class, 'send'])->name('messages.send');
    Route::get('/messages/fetch-new/{receiverId}/{lastMessageId}', [MessagesController::class, 'fetchNew'])->name('messages.fetchNew');

    // Jitsi call join
    Route::get('/call/join/{roomId}', function ($roomId) {
        return view('call-join', ['roomId' => $roomId]);
    })->name('call.join');
    Route::post('/messages/call-invitation', [MessagesController::class, 'sendCallInvitation']);
});