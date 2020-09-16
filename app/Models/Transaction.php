<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'ref_id', 'pipline_id', 'shop_id',
        'shop_service_id', 'amount', 'from_account_id', 'to_account_id',
        'type', 'status', 'reason'
    ];
}
