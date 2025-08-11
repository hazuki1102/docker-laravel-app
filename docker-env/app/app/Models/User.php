<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks')->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }

    public function likes() {
        return $this->hasMany(\App\Models\Like::class);
    }
    public function likedPosts() {
        return $this->belongsToMany(\App\Models\Post::class, 'likes')->withTimestamps();
    }

    public function productLikes()
    {
        return $this->hasMany(\App\Models\ProductLike::class);
    }
}
