<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'account_id', 'ref_id', 'name', 'domain'
    ];

    public function account() {
        return $this->hasOne(Account::class);
    }
}
