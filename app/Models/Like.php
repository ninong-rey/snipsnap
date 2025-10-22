<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
    ];

    // Like belongs to a video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // Like belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
