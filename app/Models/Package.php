<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'shop_id', 'amount', 'account_id', 'status', 'type', 'reason', 'package_json', 'shop_service_id', 'shop_service_id',
        'quantity'
    ];

    const STATUS_DOING = 1;
    const STATUS_WAITING = 2;
    const STATUS_FAIL = 3;
    const STATUS_SUCCESS = 4;
}
