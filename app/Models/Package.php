<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'shop_id', 'amount', 'account_id', 'status', 'type', 'reason', 'package_json', 'shop_service_id', 'shop_service_id'
    ];
}
