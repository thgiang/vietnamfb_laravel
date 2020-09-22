<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'fullname', 'username', 'email', 'tel', 'balance', 'is_root', 'password', 'fb_id', 'google_id', 'shop_id',
        'has_shop_id'
    ];

    protected $hidden = ['password'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'id', 'account_id');
    }
}
