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

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES - For testing only
|--------------------------------------------------------------------------
*/
Route::get('/test-cloudinary-config', function() {
    try {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return response()->json(['error' => 'Missing Cloudinary environment variables'], 500);
        }

        $cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ]
        ]);

        $result = $cloudinary->adminApi()->ping();

        return response()->json([
            'success' => true,
            'message' => 'Cloudinary configuration is working!',
            'ping_result' => $result
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/video-debug', function() {
    return view('video-debug');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [WebController::class, 'index'])->name('home');

// Test notifications
Route::get('/test-notification', function () {
    $user = User::first();
    if ($user) {
        $user->notify(new TestNotification("Hello from Render!"));
        return response('Notification sent!');
    }
    return response('No user found', 404);
});

Route::get('/fake-notification', function () {
    return view('fake-notification');
});

// Serve uploaded media safely
Route::get('/media/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!File::exists($filePath)) {
        return response('File not found', 404);
    }

    return response()->file($filePath);
})->where('path', '.*');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPerform'])->name('login.perform');

    Route::get('/signup', [AuthController::class, 'signupView'])->name('signup.view');
    Route::post('/signup', [AuthController::class, 'signupPerform'])->name('signup.perform');

    Route::get('/reset', [AuthController::class, 'forgotPasswordView'])->name('password.request');
    Route::post('/reset', [AuthController::class, 'otpRequest'])->name('otp.request');

    Route::get('/reset-password', [AuthController::class, 'resetPasswordView'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'otpVerify'])->name('otp.verify');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.perform');

    // Feed
    Route::get('/web', [WebController::class, 'index'])->name('web');
    Route::get('/following', [FollowController::class, 'followingVideos'])->name('following.videos');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/{id}', [UserController::class, 'profile'])->name('users.profile');
        Route::get('/{id}/followers', [UserController::class, 'followers'])->name('users.followers');
        Route::get('/{id}/following', [UserController::class, 'following'])->name('users.following');
        Route::get('/{id}/videos', [UserController::class, 'videos'])->name('users.videos');
        Route::get('/{id}/liked-videos', [UserController::class, 'likedVideos'])->name('users.liked-videos');

        Route::get('/suggestions', [UserController::class, 'suggestions'])->name('users.suggestions');
        Route::get('/search', [UserController::class, 'search'])->name('users.search');
    });

    // Video
    Route::prefix('video')->group(function () {
        Route::get('/{id}', [VideoController::class, 'show'])->name('video.show');
        Route::post('/upload', [VideoController::class, 'store'])->name('video.upload');
        Route::post('/{id}/like', [VideoController::class, 'like'])->name('video.like');
        Route::post('/{id}/unlike', [VideoController::class, 'unlike'])->name('video.unlike');
        Route::post('/{id}/share', [VideoController::class, 'share'])->name('video.share');
    });

    // Comments
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    // Explore
    Route::get('/explore-users', [WebController::class, 'exploreUsers'])->name('explore.users');
    Route::get('/friends', [WebController::class, 'friends'])->name('friends');

    // Follow
    Route::post('/user/{id}/follow', [FollowController::class, 'toggleFollow'])->name('follow.toggle');
    Route::get('/follow/status/{user}', [FollowController::class, 'followStatus'])->name('follow.status');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::get('/unread-counts', [NotificationController::class, 'getUnreadCounts']);
        Route::get('/fetch-latest', [NotificationController::class, 'fetchLatest']);
    });

    // Messages
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessagesController::class, 'index'])->name('messages.index');
        Route::get('/{userId}', [MessagesController::class, 'show'])->name('messages.show');
        Route::post('/send', [MessagesController::class, 'send'])->name('messages.send');
        Route::get('/fetch-new/{receiverId}/{lastMessageId}', [MessagesController::class, 'fetchNew'])->name('messages.fetchNew');
        Route::post('/call-invitation', [MessagesController::class, 'sendCallInvitation']);
    });

    // Jitsi calls
    Route::get('/call/join/{roomId}', function ($roomId) {
        return view('call-join', ['roomId' => $roomId]);
    })->name('call.join');
});

// User profiles by username (optional)
Route::get('/user/{username}', [WebController::class, 'showUserProfile'])->name('user.profile');

// SPA Catch-All (React/Vue support)
Route::get('/{any}', [WebController::class, 'index'])->where('any', '.*');
