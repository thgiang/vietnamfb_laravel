<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopService extends Model
{
    protected $fillable = [
        'shop_id', 'service_id', 'service_parent_id', 'price', 'description', 'status', 'is_root'
    ];
}
