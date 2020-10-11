<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'type', 'status', 'content', 'image', 'shop_id', 'account_id'
    ];

    const STATUS_WAITING = 0;
    const STATUS_DOING = 1;
    const STATUS_DONE = 2;
}
