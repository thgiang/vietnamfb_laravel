<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\BaseController;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends BaseController
{
    public function listAccount(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $accounts = Account::with('belongShop');

        if (auth()->user()->is_root != 1) {
            $accounts = $accounts->where('shop_id', $this->shopNow);
        }

        $accounts = $accounts->orderBy('id', 'desc')->skip($skip)->limit($this->limit)->get();

        return response([
            'success' => true,
            'data' => $accounts
        ]);
    }
}
