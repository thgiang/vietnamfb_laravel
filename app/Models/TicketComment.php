<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id', 'shop_id', 'account_id', 'content', 'image', 'is_user'
    ];

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image']) {
            return env('APP_URL') . $this->attributes['image'];
        }

        return $this->attributes['image'];
    }
}
