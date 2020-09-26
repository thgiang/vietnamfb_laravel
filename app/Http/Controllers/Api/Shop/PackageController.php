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
        $this->limit = config('constants.pagination.limit', 20);
    }

    public function listOrder(Request $request) {
        $packages = Package::where('shop_id', $this->shopNow)->orderBy('id', 'desc')->paginate($this->limit);

        return response([
            'success' => true,
            'data' => $packages
        ]);
    }
}
