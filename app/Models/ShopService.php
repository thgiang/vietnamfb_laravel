<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopService extends Model
{
    protected $fillable = [
        'shop_id', 'service_id', 'service_parent_id', 'price', 'description', 'status', 'is_root', 'priority'
    ];

    public function subMenus() {
        return $this->hasMany(ShopService::class, 'service_parent_id', 'service_id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
