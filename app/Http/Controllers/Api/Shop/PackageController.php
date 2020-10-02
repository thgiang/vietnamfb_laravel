<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\BaseController;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function listOrder(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $packages = Package::with(['account', 'shopService' => function($q) {
            return $q->with('service');
        }, 'shop']);

        if (auth()->user()->is_root != 1) {
            $packages = $packages->where('shop_id', $this->shopNow);
        }

        $packages = $packages->orderBy('id', 'desc')->skip($skip)->limit($this->limit)->get();

        return response([
            'success' => true,
            'data' => $packages
        ]);
    }
}
