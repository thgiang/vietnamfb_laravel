<?php

namespace App\Http\Controllers\Api;

use App\Models\ShopService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends BaseController
{
    public function menus(Request $request) {
        $shopNow = $this->shopNow;

        $menus = ShopService::with(['service', 'subMenus' => function($q) use ($shopNow) {
            $q->with('service')->where('shop_id', $shopNow)->orderBy('priority', 'asc');
        }])->where('shop_id', $this->shopNow)
            ->where('status', 1)->where('service_parent_id', -1)
            ->orderBy('priority', 'asc')->get();

        return response([
            'success' => true,
            'data' => $menus
        ]);
    }
}
