<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pipline extends Model
{
    protected $fillable = [
        'shop_id', 'amount', 'account_id', 'status', 'type', 'reason'
    ];
}
