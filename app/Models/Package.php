<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'shop_id', 'amount', 'account_id', 'status', 'type', 'reason', 'package_json', 'shop_service_id', 'shop_service_id',
        'quantity', 'status_process', 'quantity_processed', 'quantity_showed', 'code'
    ];

    protected $hidden = [
        'quantity_processed'
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

    public function account() {
        return $this->belongsTo(Account::class)->select(['id', 'fullname', 'username', 'email', 'tel', 'balance', 'shop_id', 'has_shop_id']);
    }

    public function shopService() {
        return $this->belongsTo(ShopService::class);
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }
}
