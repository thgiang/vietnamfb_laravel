<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'type', 'status', 'content', 'image', 'shop_id', 'account_id', 'updated_by'
    ];

    const STATUS_WAITING = 0;
    const STATUS_DOING = 1;
    const STATUS_DONE = 2;

    const TYPE_BUFF_FB = 1;
    const TYPE_FB_BUFF_FOLLOW = 2;

    public function comments() {
        return $this->hasMany(TicketComment::class);
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image']) {
            return env('APP_URL') . $this->attributes['image'];
        }

        return $this->attributes['image'];
    }
}
