<?php

namespace App\Http\Controllers\Api\Shop;

use App\Helpers\Utils;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\TopUpRequest;
use App\Models\Account;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BalanceController extends BaseController
{
    public function topUp(TopUpRequest $request) {
        $accountId = $request->input('account_id');
        $amount = $request->input('amount');

        $amount = preg_replace('/[^0-9]/', '', $amount);

        if ($amount <= 0) {
            return response(Utils::FailedResponse('Số tiền nạp vào phải lớn hơn 0'));
        }

        // chu shop chi duoc top-up cho nguoi khac duoi minh 1 cap, khong duoc tu top-up chinh minh
        if (auth()->user()->id == $accountId) {
            return response(Utils::FailedResponse('Hãy liên hệ cấp trên của bạn để thực hiện nạp tiền vào tài khoản này'));
        }

        $account = Account::where('shop_id', $this->shopNow)->where('id', $accountId)->first();

        if (empty($account)) {
            return response(Utils::FailedResponse('Không tìm thấy tài khoản này trên trang web của bạn'));
        }

        DB::beginTransaction();

        try {
            Transaction::create([
                'package_id' => Transaction::TOP_UP_PACKAGE_ID,
                'shop_id' => $this->shopNow,
                'shop_service_id' => Transaction::TOP_UP_SHOP_SERVICE_ID,
                'amount' => $amount,
                'from_account_id' => $accountId,
                'to_account_id' => auth()->user()->id,
                'type' => Transaction::TYPE_TOP_UP,
                'status' => Transaction::STATUS_SUCCESS,
                'reason' => 'Nạp tiền ' . $amount . ' thành công',
                'quantity' => 1
            ]);

            $account->update([
                'balance' => $account->balance + $amount
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            return response(Utils::FailedResponse('Lỗi hệ thống, vui lòng liên hệ admin để được giải quyết. Chi tiết lỗi: ' . $ex->getMessage()));
        }

        return response([
            'success' => true,
            'message' => 'Nạp tiền thành công, vui lòng kiểm tra số dư tài khoản'
        ]);
    }
}
