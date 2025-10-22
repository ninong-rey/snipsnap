<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    /**
     * The user who is following another user.
     * Example: user_id = the person who clicked "Follow"
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * The user being followed.
     * Example: user_id = the one who is *being followed*
     */
    public function followed() {
    return $this->belongsTo(User::class, 'following_id');
}

}
