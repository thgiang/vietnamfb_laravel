<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'parent_id', 'slug', 'sku', 'description', 'status'
    ];

    const TOP_UP = 0;
    const FB_BUFF_SUB = 1;

    const STATUS_DOING = 1;

    public function shopService() {
        return $this->hasMany(ShopService::class);
    }
}
