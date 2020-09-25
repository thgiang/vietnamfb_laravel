<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'shop_id', 'amount', 'account_id', 'status', 'type', 'reason', 'package_json', 'shop_service_id', 'shop_service_id',
        'quantity', 'status_process'
    ];

    const STATUS_ORDER_DOING = 1;
    const STATUS_ORDER_WAITING = 2;
    const STATUS_ORDER_FAIL = 3;
    const STATUS_ORDER_SUCCESS = 4;
    const STATUS_REFUND_FAIL = 5;
    const STATUS_REFUND_SUCCESS = 6;
    const STATUS_ADMIN_CANCEL = 7;
    const STATUS_CUSTOMER_CANCEL = 8;

    const STATUS_PROCESS_WAIT = 1;
    const STATUS_PROCESS_DOING = 2;
    const STATUS_PROCESS_FAIL = 3;
    const STATUS_PROCESS_SUCCESS = 4;
    const STATUS_PROCESS_CANCEL = 5;
}
