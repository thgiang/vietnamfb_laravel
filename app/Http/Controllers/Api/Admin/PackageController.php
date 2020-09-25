<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Admin\RefundSubmitRequest;
use App\Models\Account;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends BaseController
{
    public function refundSubmit(RefundSubmitRequest $request) {
        $packageId = $request->input('package_id');
        $quantity = $request->input('quantity');

        $package = Package::find($packageId);

        if (empty($package)) {
            return response(Utils::FailedResponse('Không tìm thấy đơn hàng này'));
        }

        $transactions = Transaction::where('package_id', $package->id)->get();

        DB::beginTransaction();

        try {
            foreach ($transactions as $transaction) {
                $account = Account::find($transaction->from_account_id);
                if (empty($account)) {
                    continue;
                }

                $price = ceil($transaction->amount / $transaction->quantity);

                $refundAmount = $price * $quantity;

                $refundDataTransaction = [
                    'refund_transaction_id' => $transaction->id,
                    'package_id' => $package->id,
                    'shop_id' => $transaction->shop_id,
                    'shop_service_id' => $transaction->shop_service_id,
                    'from_account_id' => $transaction->from_account_id,
                    'to_account_id' => $transaction->to_account_id,
                    'type' => Transaction::TYPE_REFUND,
                    'status' => Transaction::STATUS_SUCCESS,
                    'quantity' => $quantity,
                    'amount' => -1 * $refundAmount
                ];

                Transaction::create($refundDataTransaction);

                // cong tien vao cho account balance
                $account->update([
                    'balance' => $account->balance + $refundAmount
                ]);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            return response(Utils::FailedResponse('Có chút lỗi xảy ra, vui lòng liên hệ admin để được giải quyết. Mã lỗi: ' . $ex->getMessage()));
        }

        return response([
            'success' => true,
            'message' => 'Hoàn trả tiền thành công'
        ]);
    }
}
