<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth; // Add this import

class Video extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'caption',
        'url',
        'thumbnail_url', 
        'views',
        'likes_count',
        'comments_count',
        'shares_count',
    ];

    // Default values for counts
    protected $attributes = [
        'views' => 0,
        'likes_count' => 0,
        'comments_count' => 0,
        'shares_count' => 0,
    ];

    // Cast attributes to appropriate types
    protected $casts = [
        'views' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Append custom attributes to JSON
    protected $appends = [
        'is_liked',
        'likes_count_formatted',
        'comments_count_formatted',
    ];
    // Add this method to your Video model
public function shareByUser($userId): void
{
    // Check if already shared
    if (!$this->shares()->where('user_id', $userId)->exists()) {
        $this->shares()->create(['user_id' => $userId]);
        $this->incrementShares();
    }
}

    // Video belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Video has many video likes (using VideoLike model)
    public function videoLikes(): HasMany
    {
        return $this->hasMany(VideoLike::class);
    }

    // Video has many comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getIsLikedAttribute(): bool 
{
    if (!\Illuminate\Support\Facades\Auth::check()) { // Fully qualified
        return false;
    }
    
    return \App\Models\VideoLike::where('video_id', $this->id)
                               ->where('user_id', \Illuminate\Support\Facades\Auth::id()) // Fully qualified
                               ->exists();
}
    // Get formatted likes count (e.g., 1.2K, 5.3M)
    public function getLikesCountFormattedAttribute(): string
    {
        return $this->formatCount($this->likes_count ?? 0);
    }

    // Get formatted comments count
    public function getCommentsCountFormattedAttribute(): string
    {
        return $this->formatCount($this->comments_count ?? 0);
    }

    // Get formatted views count
    public function getViewsCountFormattedAttribute(): string
    {
        return $this->formatCount($this->views ?? 0);
    }

    // Helper method to format large numbers
    private function formatCount(int $count): string
    {
        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        }
        
        if ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }
        
        return (string) $count;
    }

    // Increment views count
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    // Increment likes count
    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

    // Decrement likes count
    public function decrementLikes(): void
    {
        $this->decrement('likes_count');
    }

    // Increment comments count
    public function incrementComments(): void
    {
        $this->increment('comments_count');
    }

    // Decrement comments count
    public function decrementComments(): void
    {
        $this->decrement('comments_count');
    }

    // Increment shares count
    public function incrementShares(): void
    {
        $this->increment('shares_count');
    }

    // Scope for popular videos
    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc');
    }

    // Scope for recent videos
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope for videos by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Check if video belongs to user
    public function isOwnedBy($userId): bool
    {
        return $this->user_id === $userId;
    }
    // Alias for videoLikes - if you want to use both names
public function likes(): HasMany
{
    return $this->videoLikes();
}
// Add this after the comments() method and before the likes() method
public function shares(): HasMany
{
    return $this->hasMany(Share::class);
}
}