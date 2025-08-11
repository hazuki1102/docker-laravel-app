<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model
{
    protected $fillable = ['user_id','product_id'];

    public function user()    { return $this->belongsTo(\App\User::class); }
    public function product() { return $this->belongsTo(\App\Models\Product::class); }
}
