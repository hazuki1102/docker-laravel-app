<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'post_id', 'title', 'price', 'file_path', 'caption', 'tags',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function likes()
    {
        return $this->hasMany(\App\Models\ProductLike::class);
    }
    public function isLikedBy(?\App\User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

}
