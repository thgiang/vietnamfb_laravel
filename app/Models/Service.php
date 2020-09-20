<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'parent_id', 'slug', 'sku', 'description', 'status'
    ];

    const SKU = [
        'FB_BUFF_AUTO' => 'fb.buff.auto',
        'FB_BUFF_LIKE' => 'fb.buff.like'
    ];
}
