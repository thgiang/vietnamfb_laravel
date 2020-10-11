<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id', 'shop_id', 'account_id', 'content', 'image'
    ];
}
