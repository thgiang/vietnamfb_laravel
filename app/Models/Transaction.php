<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'refund_transaction_id', 'package_id', 'shop_id',
        'shop_service_id', 'amount', 'from_account_id', 'to_account_id',
        'type', 'status', 'reason', 'quantity'
    ];

    const TYPE_NEW_ORDER = 1;
    const TYPE_REFUND = 2;

    const STATUS_DOING = 1;
    const STATUS_WAITING = 2; // neu trong pipline co 1 thang ref bi het tien thi phai waiting 30p sau se set fail neu van waiting
    const STATUS_FAIL = 3;
    const STATUS_SUCCESS = 4;
}
