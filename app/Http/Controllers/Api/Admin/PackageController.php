<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Admin\ProcessStartRequest;
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

        // todo: check dieu kien don de hoan tien

        $package->update([
            'status_process' => Package::STATUS_PROCESS_DOING
        ]);

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
                    'amount' => -1 * $refundAmount,
                    'reason' => 'Hoàn trả tiền thành công'
                ];

                Transaction::create($refundDataTransaction);

                // cong tien vao cho account balance
                $account->update([
                    'balance' => $account->balance + $refundAmount
                ]);
            }

            $package->update([
                'status' => Package::STATUS_REFUND_SUCCESS,
                'status_process' => Package::STATUS_PROCESS_SUCCESS,
                'reason' => 'Hoàn trả tiền thành công'
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            $package->update([
                'status' => Package::STATUS_REFUND_FAIL,
                'status_process' => Package::STATUS_PROCESS_FAIL,
                'reason' => 'Lỗi hệ thống: ' . $ex->getMessage()
            ]);

            return response(Utils::FailedResponse('Có chút lỗi xảy ra, vui lòng liên hệ admin để được giải quyết. Chi tiết lỗi: ' . $ex->getMessage()));
        }

        return response([
            'success' => true,
            'message' => 'Hoàn trả tiền thành công'
        ]);
    }

    public function processStart(ProcessStartRequest $request) {
        $packageId = $request->input('package_id');

        $package = Package::find($packageId);
        if (empty($package)) {
            return response(Utils::FailedResponse('Không tìm thấy đơn hàng này'));
        }

        if ($package->status != Package::STATUS_ORDER_SUCCESS) {
            return response(Utils::FailedResponse('Chỉ có thể start 1 dịch vụ có trạng thái Đặt hàng thành công'));
        }

        $package->update([
            'status_process' => Package::STATUS_PROCESS_DOING
        ]);

        // todo: tich hop api mfb

        // se co 1 cronjob chay hang 10 phut check xem co nhung don hang nao dang process doing thi call api fb de lay so subs
    }

    public function update($id, Request $request) {
        $package = Package::find($id);
        if (empty($package)) {
            return response(Utils::FailedResponse('Không tìm thấy đơn hàng này'));
        }

        $data = $request->all();
        $package->update($data);

        return response([
            'success' => true,
            'message' => 'Cập nhật thành công'
        ]);
    }
}
