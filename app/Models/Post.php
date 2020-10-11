<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'account_id', 'content', 'image', 'like_count', 'comment_count', 'status', 'type', 'shop_id'
    ];

    public function account() {
        return $this->belongsTo(Account::class)->select('id', 'fullname', 'username');
    }

    public function getImageAttribute()
    {
        return env('APP_URL') . $this->attributes['image'];
    }
}
