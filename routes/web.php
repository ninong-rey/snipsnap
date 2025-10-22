<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [WebController::class, 'index'])->name('home');

// Authentication
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'loginPerform'])->name('login.perform');

Route::get('/signup', [AuthController::class, 'signupView'])->name('signup.view');
Route::post('/signup', [AuthController::class, 'signupPerform'])->name('signup.perform');

// Forgot / Reset Password (OTP Flow)
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

    // Upload
    Route::get('/upload', [VideoController::class, 'create'])->name('upload');
    Route::post('/upload', [VideoController::class, 'store'])->name('upload.store');

    // Following Feed
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
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessagesController::class, 'send'])->name('messages.send');
    Route::get('/messages/fetch-new/{receiverId}/{lastMessageId}', [MessagesController::class, 'fetchNew'])->name('messages.fetchNew');

    // Jitsi call join route
Route::get('/call/join/{roomId}', function ($roomId) {
    return view('call-join', ['roomId' => $roomId]);
})->name('call.join')->middleware('auth');
Route::post('/messages/call-invitation', [MessagesController::class, 'sendCallInvitation']);
});