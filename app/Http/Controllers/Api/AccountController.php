<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;

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

        // tinh bieu do
        $perMonthAnalytics = [];

        for ($i = 0; $i <= 5; $i++) {
            $startMonth = Carbon::now()->subMonths($i)->startOfMonth()->toDateTimeString();
            $endMonth = Carbon::now()->subMonths($i)->endOfMonth()->toDateTimeString();

            $amountTopUpThisMonth = Transaction::selectRaw('sum(amount) as amount_this_month')->where('shop_id', $this->shopNow)->where('from_account_id', auth()->user()->id)
                ->where('type', Transaction::TYPE_TOP_UP)->whereBetween('created_at', [$startMonth, $endMonth])
                ->where('status', Transaction::STATUS_SUCCESS)->first();

            $amountOrderThisMonth = Transaction::selectRaw('sum(amount) as amount_this_month')->where('shop_id', $this->shopNow)->where('from_account_id', auth()->user()->id)
                ->where('type', Transaction::TYPE_NEW_ORDER)->whereBetween('created_at', [$startMonth, $endMonth])
                ->where('status', Transaction::STATUS_SUCCESS)->first();

            $perMonthAnalytics[Carbon::now()->subMonths($i)->month] = [
                'top_up' => !empty($amountTopUpThisMonth->amount_this_month) ? $amountTopUpThisMonth->amount_this_month : 0,
                'order' => !empty($amountOrderThisMonth->amount_this_month) ? $amountOrderThisMonth->amount_this_month : 0
            ];
        }

        return response([
            'success' => true,
            'data' => $account,
            'analytics' => $perMonthAnalytics
        ]);
    }
}
