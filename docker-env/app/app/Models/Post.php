<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'image_path',
        'hashtags',
        'materials',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likes() {
    return $this->hasMany(\App\Models\Like::class);
    }
    public function likedUsers() {
        return $this->belongsToMany(\App\Models\User::class, 'likes')->withTimestamps();
    }
    public function isLikedBy(?\App\Models\User $user): bool {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

}

