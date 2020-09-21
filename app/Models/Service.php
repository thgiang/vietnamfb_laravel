<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'parent_id', 'slug', 'sku', 'description', 'status'
    ];

    const FB_BUFF_SUB = 1;
}
