<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id', 'post_id', 'title', 'price', 'file_path',
    ];

    public $timestamps = false;
}
