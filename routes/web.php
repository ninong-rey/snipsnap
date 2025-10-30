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

// Add to your web.php routes
Route::get('/test-web-fix', function() {
    $videos = \App\Models\Video::with(['user', 'comments.user'])
        ->withCount(['likes', 'comments', 'shares'])
        ->latest()
        ->get()
        ->map(function ($video) {
            if (empty($video->file_path) && !empty($video->url)) {
                $video->file_path = $video->url;
            }
            return $video;
        });

    echo "<h1>Fixed WebController Output</h1>";
    foreach ($videos as $video) {
        echo "Video {$video->id}: {$video->file_path} - Created: {$video->created_at}<br>";
        echo "Video URL: " . asset('storage/' . $video->file_path) . "<br><br>";
    }
    
    return "Test complete - " . count($videos) . " videos";
});
// Add this temporary route to web.php
Route::get('/debug-video-18', function() {
    $video18 = \App\Models\Video::find(18);
    
    if (!$video18) {
        return "❌ Video 18 NOT FOUND in database!";
    }
    
    return "✅ Video 18 FOUND:<br>" . 
           "ID: {$video18->id}<br>" .
           "File Path: {$video18->file_path}<br>" .
           "URL: {$video18->url}<br>" .
           "Created: {$video18->created_at}<br>" .
           "User ID: {$video18->user_id}";
});
Route::get('/debug-all-videos-raw', function() {
    $videos = \DB::table('videos')->orderBy('created_at', 'desc')->get();
    
    echo "<h1>Raw Database Videos (No Eloquent):</h1>";
    foreach ($videos as $video) {
        echo "Video {$video->id}: {$video->file_path} - Created: {$video->created_at}<br>";
    }
    
    return "Total: " . $videos->count() . " videos";
});
Route::get('/test-upload-debug', function() {
    try {
        // Test if we can create a video record manually
        $testVideo = new \App\Models\Video();
        $testVideo->user_id = 1; // Use an existing user ID
        $testVideo->url = 'videos/test-debug.mp4';
        $testVideo->file_path = 'videos/test-debug.mp4';
        $testVideo->caption = 'Test debug video';
        
        $saved = $testVideo->save();
        
        if ($saved) {
            return "✅ Manual video creation SUCCESS! New ID: " . $testVideo->id;
        } else {
            return "❌ Manual video creation FAILED - no error thrown";
        }
        
    } catch (\Exception $e) {
        return "❌ Manual video creation ERROR: " . $e->getMessage();
    }
});
Route::get('/check-upload-form', function() {
    return "
    <form action='/upload' method='POST' enctype='multipart/form-data'>
        <input type='file' name='video' accept='video/*' required>
        <input type='text' name='caption' placeholder='Caption'>
        <button type='submit'>Upload Test</button>
        " . csrf_field() . "
    </form>
    ";
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