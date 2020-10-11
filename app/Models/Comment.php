<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id', 'parent_id', 'content', 'account_id', 'shop_id'
    ];

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function account() {
        return $this->belongsTo(Account::class);
    }
}
