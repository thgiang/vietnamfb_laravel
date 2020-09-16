<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'fullname', 'username', 'email', 'tel', 'balance', 'is_root', 'password', 'fb_id', 'google_id'
    ];

    protected $hidden = ['password'];
}
