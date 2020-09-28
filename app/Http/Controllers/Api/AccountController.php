<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends BaseController
{
    public function analytics() {
        $account = Account::select('id', 'fullname', 'username', 'balance', 'email')->where('shop_id', $this->shopNow)->where('id', auth()->user()->id)->first();

        if (empty($account)) {
            return response(Utils::FailedResponse('Không tìm thấy tài khoản này trên trang web của bạn'));
        }

        $thisMonth = Carbon::now()->startOfMonth()->toDateTimeString();

        $amountThisMonth = Transaction::selectRaw('sum(amount) as amount_this_month')->where('shop_id', $this->shopNow)->where('from_account_id', auth()->user()->id)
            ->where('type', Transaction::TYPE_TOP_UP)->where('created_at', '>=', $thisMonth)->where('status', Transaction::STATUS_SUCCESS)->first();

        $account->amount_this_month = !empty($amountThisMonth->amount_this_month) ? $amountThisMonth->amount_this_month : 0;

        $amountTotal = Transaction::selectRaw('sum(amount) as amount_total')->where('shop_id', $this->shopNow)->where('from_account_id', auth()->user()->id)
            ->where('type', Transaction::TYPE_TOP_UP)->where('status', Transaction::STATUS_SUCCESS)->first();

        $account->amount_total = !empty($amountTotal->amount_total) ? $amountTotal->amount_total : 0;

        return response([
            'success' => true,
            'data' => $account
        ]);
    }
}
