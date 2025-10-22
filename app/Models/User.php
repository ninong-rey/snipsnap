<?php

    namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'phone_number',
        'last_activity',
    ];

    // -----------------------------
    // FOLLOW RELATIONSHIPS
    // -----------------------------
    // Users this user is following
// Users this user is following
public function following()
{
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                ->withTimestamps();
}

// Users who follow this user
public function followers()
{
    return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                ->withTimestamps();
}

// Optional helper
public function isFollowing(User $user)
{
    return $this->following()->where('following_id', $user->id)->exists();
}



    // -----------------------------
    // VIDEO RELATIONSHIP
    // -----------------------------
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    // -----------------------------
    // LIKE RELATIONSHIP
    // -----------------------------
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // -----------------------------
    // MESSAGE RELATIONSHIPS
    // -----------------------------
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // -----------------------------
    // NOTIFICATIONS
    // -----------------------------
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // -----------------------------
    // HELPERS
    // -----------------------------
    public function getProfileImageAttribute($value)
    {
        return $value && file_exists(public_path('storage/' . $value))
            ? asset('storage/' . $value)
            : asset('image/default-avatar.png');
    }

    public function updateLastActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    public function isOnline(): bool
    {
        return $this->last_activity && now()->diffInMinutes($this->last_activity) <= 5;
    }
    // In your User model - make sure this exists
public function likedVideos()
{
    return $this->belongsToMany(Video::class, 'video_likes', 'user_id', 'video_id')
                ->withTimestamps();
}
}
