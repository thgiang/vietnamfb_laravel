<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $shopNow;

    public function __construct(Request $request)
    {
        $this->shopNow = $request->header('shopId');
    }
}