<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'name', 'type', 'status', 'setting', 'shop_id'
    ];

    const TYPE_TOKEN = 1;
}
