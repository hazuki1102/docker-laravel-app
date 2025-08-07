<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'user_tokens';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'rest_password_access_key',
        'rest_password_expire_data',
    ];

    protected $dates = ['rest_password_expire_data'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
