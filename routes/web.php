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

// Add this with your other debug routes in web.php
Route::get('/upload-debug', function() {
    echo "<h2>Upload Debug Info:</h2>";
    echo "POST Max Size: " . ini_get('post_max_size') . "<br>";
    echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "<br>";
    echo "Max File Uploads: " . ini_get('max_file_uploads') . "<br>";
    echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
    
    // Test if storage is writable
    echo "Storage writable: " . (is_writable(storage_path()) ? 'Yes' : 'No') . "<br>";
    echo "Public storage writable: " . (is_writable(storage_path('app/public')) ? 'Yes' : 'No') . "<br>";
    
    // Test file storage
    try {
        $testPath = 'videos/test.txt';
        Storage::disk('public')->put($testPath, 'test content');
        $exists = Storage::disk('public')->exists($testPath);
        echo "File storage test: " . ($exists ? '✅ WORKS' : '❌ FAILED') . "<br>";
        
        if ($exists) {
            Storage::disk('public')->delete($testPath);
        }
    } catch (\Exception $e) {
        echo "File storage error: " . $e->getMessage() . "<br>";
    }
    
    return "Debug complete";
});
Route::get('/test-simple', function() {
    return "Simple test route works!";
});
Route::get('/test-tiny-upload', function() {
    try {
        // Test with a tiny text file (not video)
        $testContent = "test file content";
        $path = 'videos/test-' . time() . '.txt';
        
        Storage::disk('public')->put($path, $testContent);
        
        // Try to create database record
        $video = \App\Models\Video::create([
            'user_id' => 1, // Use existing user
            'url' => $path,
            'file_path' => $path,
            'caption' => 'Test tiny upload',
        ]);
        
        return "✅ Tiny upload test SUCCESS! Video ID: " . $video->id;
        
    } catch (\Exception $e) {
        return "❌ Tiny upload test FAILED: " . $e->getMessage();
    }
});
Route::get('/debug-all-videos-raw', function() {
    $videos = \DB::table('videos')->orderBy('created_at', 'desc')->get();
    
    echo "<h1>Raw Database Videos (No Eloquent):</h1>";
    foreach ($videos as $video) {
        echo "Video {$video->id}: {$video->file_path} - Created: {$video->created_at}<br>";
    }
    
    return "Total: " . $videos->count() . " videos";
});
Route::get('/test-real-upload', function() {
    try {
        // Simulate a real file upload
        $tempFile = tempnam(sys_get_temp_dir(), 'test_video');
        file_put_contents($tempFile, 'fake video content');
        
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempFile,
            'test-video.mp4',
            'video/mp4',
            null,
            true
        );
        
        // Test the store method
        $path = $uploadedFile->store('videos', 'public');
        echo "Store returned: " . $path . "<br>";
        
        // Test creating record
        $video = \App\Models\Video::create([
            'user_id' => 1,
            'url' => $path,
            'file_path' => $path,
            'caption' => 'Test real upload',
        ]);
        
        return "✅ Real upload test SUCCESS! Video ID: " . $video->id . " with path: " . $video->file_path;
        
    } catch (\Exception $e) {
        return "❌ Real upload test FAILED: " . $e->getMessage();
    }
});
Route::get('/check-video-model', function() {
    $video = new \App\Models\Video();
    echo "Fillable fields: ";
    print_r($video->getFillable());
    echo "<br><br>";
    
    // Test creating a video with file_path
    try {
        $testVideo = \App\Models\Video::create([
            'user_id' => 1,
            'url' => 'videos/test-url.mp4',
            'file_path' => 'videos/test-file-path.mp4',
            'caption' => 'Test model update',
        ]);
        
        echo "✅ Video created with file_path: '" . $testVideo->file_path . "'";
        return "<br>Model test complete";
        
    } catch (\Exception $e) {
        return "❌ Model test failed: " . $e->getMessage();
    }
});
// Add these cache routes
Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    return "Cache cleared!";
});

Route::get('/clear-all', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    return "All caches cleared!";
});
Route::get('/test-upload-form', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Test Upload Form</title></head>
    <body>
        <h1>Simple Upload Test</h1>
        <form action="/upload" method="POST" enctype="multipart/form-data" id="uploadForm">
            <input type="file" name="video" accept="video/*" required>
            <input type="text" name="caption" placeholder="Caption">
            <button type="submit">Upload Test</button>
            ' . csrf_field() . '
        </form>
        
        <script>
            document.getElementById("uploadForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch("/upload", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Upload response:", data);
                    alert("Response: " + JSON.stringify(data));
                })
                .catch(error => {
                    console.error("Upload error:", error);
                    alert("Error: " + error);
                });
            });
        </script>
    </body>
    </html>
    ';
});
Route::get('/fix-storage-link', function() {
    try {
        // Remove existing link if it exists
        if (file_exists(public_path('storage'))) {
            unlink(public_path('storage'));
        }
        
        // Create new symlink
        symlink(storage_path('app/public'), public_path('storage'));
        
        return "✅ Storage symlink created! Videos should now be accessible.";
    } catch (\Exception $e) {
        return "❌ Error creating symlink: " . $e->getMessage();
    }
});
// Add this to your web.php - serves videos directly
Route::get('/videos/{filename}', function($filename) {
    $path = storage_path('app/public/videos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path, [
        'Content-Type' => 'video/mp4',
    ]);
})->where('filename', '.*');
// Basic test route
Route::get('/test-routes', function() {
    return "✅ Routes are working!";
});

// Test if videos route exists
Route::get('/test-videos-route', function() {
    return "✅ Videos route is registered!";
});

// Simple video test
Route::get('/test-video', function() {
    $filename = 'mjMnskzybXmYOmAAaEtNUinJGEi2skeNJAWfhfgv.mp4';
    $path = storage_path('app/public/videos/' . $filename);
    
    if (!file_exists($path)) {
        return "❌ Video file not found at: " . $path;
    }
    
    return "✅ Video file exists: " . $path;
});
// Fix Render storage setup
Route::get('/fix-render-storage', function() {
    try {
        // Create storage directories
        if (!file_exists(storage_path('app/public/videos'))) {
            mkdir(storage_path('app/public/videos'), 0755, true);
            echo "✅ Created videos directory<br>";
        }
        
        // Remove existing symlink if it exists
        if (file_exists(public_path('storage')) && is_link(public_path('storage'))) {
            unlink(public_path('storage'));
            echo "✅ Removed old symlink<br>";
        }
        
        // Create new symlink
        symlink(storage_path('app/public'), public_path('storage'));
        echo "✅ Created new symlink<br>";
        
        // Test file access
        Storage::disk('public')->put('videos/test.txt', 'test content ' . time());
        $exists = Storage::disk('public')->exists('videos/test.txt');
        
        if ($exists) {
            $testUrl = asset('storage/videos/test.txt');
            echo "✅ Test file created successfully!<br>";
            echo "Test URL: <a href='{$testUrl}' target='_blank'>{$testUrl}</a><br>";
        } else {
            echo "❌ Test file creation failed<br>";
        }
        
        return "Storage fix complete!";
        
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage() . "<br>" . $e->getTraceAsString();
    }
});

// Check current storage status
Route::get('/check-storage-status', function() {
    echo "<h2>Storage Status Check</h2>";
    
    // Check directories
    echo "Storage path exists: " . (file_exists(storage_path('app/public')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Videos directory exists: " . (file_exists(storage_path('app/public/videos')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Public storage symlink exists: " . (file_exists(public_path('storage')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Public storage is symlink: " . (is_link(public_path('storage')) ? '✅ YES' : '❌ NO') . "<br>";
    
    if (is_link(public_path('storage'))) {
        echo "Symlink target: " . readlink(public_path('storage')) . "<br>";
    }
    
    // List files in videos directory
    echo "<h3>Files in storage:</h3>";
    try {
        $files = Storage::disk('public')->files('videos');
        if (empty($files)) {
            echo "No files found<br>";
        } else {
            foreach ($files as $file) {
                echo "📁 " . $file . "<br>";
            }
        }
    } catch (\Exception $e) {
        echo "Error listing files: " . $e->getMessage() . "<br>";
    }
    
    return "Status check complete";
});
// Fix Render storage setup
Route::get('/fix-render-storage', function() {
    try {
        // Create storage directories
        if (!file_exists(storage_path('app/public/videos'))) {
            mkdir(storage_path('app/public/videos'), 0755, true);
            echo "✅ Created videos directory<br>";
        }
        
        // Remove existing symlink if it exists
        if (file_exists(public_path('storage')) && is_link(public_path('storage'))) {
            unlink(public_path('storage'));
            echo "✅ Removed old symlink<br>";
        }
        
        // Create new symlink
        symlink(storage_path('app/public'), public_path('storage'));
        echo "✅ Created new symlink<br>";
        
        // Test file access
        Storage::disk('public')->put('videos/test.txt', 'test content ' . time());
        $exists = Storage::disk('public')->exists('videos/test.txt');
        
        if ($exists) {
            $testUrl = asset('storage/videos/test.txt');
            echo "✅ Test file created successfully!<br>";
            echo "Test URL: <a href='{$testUrl}' target='_blank'>{$testUrl}</a><br>";
        } else {
            echo "❌ Test file creation failed<br>";
        }
        
        return "Storage fix complete!";
        
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage() . "<br>" . $e->getTraceAsString();
    }
});

// Check current storage status
Route::get('/check-storage-status', function() {
    echo "<h2>Storage Status Check</h2>";
    
    // Check directories
    echo "Storage path exists: " . (file_exists(storage_path('app/public')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Videos directory exists: " . (file_exists(storage_path('app/public/videos')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Public storage symlink exists: " . (file_exists(public_path('storage')) ? '✅ YES' : '❌ NO') . "<br>";
    echo "Public storage is symlink: " . (is_link(public_path('storage')) ? '✅ YES' : '❌ NO') . "<br>";
    
    if (is_link(public_path('storage'))) {
        echo "Symlink target: " . readlink(public_path('storage')) . "<br>";
    }
    
    // List files in videos directory
    echo "<h3>Files in storage:</h3>";
    try {
        $files = Storage::disk('public')->files('videos');
        if (empty($files)) {
            echo "No files found<br>";
        } else {
            foreach ($files as $file) {
                echo "📁 " . $file . "<br>";
            }
        }
    } catch (\Exception $e) {
        echo "Error listing files: " . $e->getMessage() . "<br>";
    }
    
    return "Status check complete";
});
// Temporary fix - clear broken storage references
Route::get('/fix-storage-403', function() {
    // This will regenerate proper storage links
    \Artisan::call('storage:link');
    
    // Clear all caches
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    \Artisan::call('route:clear');
    
    return "Storage fixed and caches cleared! 403 errors should be gone.";
});
// Debug routes for testing uploads
Route::get('/test-upload-page', function() {
    return view('test-upload');
});

Route::post('/test-upload', function(\Illuminate\Http\Request $request) {
    \Log::info('=== UPLOAD DEBUG START ===');
    \Log::info('Has file: ' . ($request->hasFile('video') ? 'YES' : 'NO'));
    
    if ($request->hasFile('video')) {
        $file = $request->file('video');
        \Log::info('File details:', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ]);
        
        try {
            // Test Cloudinary upload
            $upload = $file->storeOnCloudinary('videos');
            
            \Log::info('Cloudinary upload success:', [
                'secure_url' => $upload->getSecurePath(),
                'public_id' => $upload->getPublicId()
            ]);
            
            // Save to database - use a default user ID for testing
            $video = new \App\Models\Video();
            $video->url = $upload->getSecurePath();
            $video->public_id = $upload->getPublicId();
            $video->user_id = 1; // Default user ID for testing
            $video->save();
            
            \Log::info('Video saved to database with ID: ' . $video->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Upload successful!',
                'url' => $video->url,
                'video_id' => $video->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    return response()->json([
        'success' => false,
        'error' => 'No file received'
    ], 400);
});

Route::get('/check-videos', function() {
    $videos = \App\Models\Video::all();
    return response()->json($videos->toArray());
});
Route::get('/video-debug', function() {
    return view('video-debug');
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

    // Video Routes
Route::get('/video/{id}', [VideoController::class, 'show'])->name('video.show');
Route::post('/video/upload', [VideoController::class, 'store'])->name('video.upload');
Route::post('/video/{id}/like', [VideoController::class, 'like'])->name('video.like');
Route::post('/video/{id}/unlike', [VideoController::class, 'unlike'])->name('video.unlike');
Route::post('/video/{id}/share', [VideoController::class, 'share'])->name('video.share');

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