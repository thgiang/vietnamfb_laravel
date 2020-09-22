<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Models\Account;
use App\Models\Package;
use App\Models\Service;
use App\Models\Shop;
use App\Models\ShopService;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    private function _validateAddOrder($serviceType, $data) {
        // todo: add logic
        if ($serviceType == Service::FB_BUFF_SUB) {
            return $this->_validateFbBuffSub($data);
        }

        return 'Không tồn tại dịch vụ này';
    }

    private function _validateFbBuffSub($data) {
        if (empty($data['shop_service_id'])) {
            return 'Thiếu id dịch vụ: `shop_service_id`';
        }

        if (empty($data['service_type']) and !in_array($data['service_type'], [Service::FB_BUFF_SUB])) {
            return 'Thiếu loại dịch vụ: `service_type`';
        }

        if (empty($data['quantity']) || $data['quantity'] <= 0) {
            return 'Số lượng mua phải lớn hơn 0: `quantity`';
        }

        return '';
    }

    public function addOrder(Request $request) {
        $data = $request->all();

        $validate = $this->_validateAddOrder($data['service_type'], $data);

        if (!empty($validate)) {
            return response(Utils::FailedResponse($validate));
        }

        $shopService = ShopService::where('shop_id', $this->shopNow)->where('status', 1)->where('id', $data['shop_service_id'])->first();

        if (empty($shopService)) {
            return response(Utils::FailedResponse('Dịch vụ không tìm thấy hoặc đang trong quá trình nâng cấp dịch vụ'));
        }

        // tinh amount
        $amount = $shopService->price * intval($data['quantity']);

        // tao don
        $package = Package::create([
            'shop_id' => $this->shopNow,
            'account_id' => auth()->user()->id,
            'shop_service_id' => $data['shop_service_id'],
            'type' => $data['service_type'],
            'status' => Package::STATUS_DOING,
            'amount' => $amount,
            'quantity' => $data['quantity'],
            'package_json' => json_encode($data)
        ]);

        // tao cac transactions
        $transactionService = new TransactionService();

        $transactionPackageStatus = $transactionService->makeTransactionPiplineForPackage($package->id);

        if (!empty($transactionPackageStatus['transaction_ids']) and count($transactionPackageStatus['transaction_ids']) > 0) {
            $dataUpdateTransactions['reason'] = $transactionPackageStatus['reason'];

            if ($transactionPackageStatus['status'] == Package::STATUS_FAIL) {
                $dataUpdateTransactions['status'] = Transaction::STATUS_FAIL;
            } else if ($transactionPackageStatus['status'] == Package::STATUS_SUCCESS) {
                $dataUpdateTransactions['status'] = Transaction::STATUS_SUCCESS;

                // tru tien cua cac account
                foreach ($transactionPackageStatus['transaction_ids'] as $transaction_id) {
                    $eachTransaction = Transaction::find($transaction_id);

                    $account = Account::find($eachTransaction->from_account_id);
                    $account->update([
                        'balance' => $account->balance - $eachTransaction->amount
                    ]);
                }
            } else if ($transactionPackageStatus['status'] == Package::STATUS_WAITING) {
                $dataUpdateTransactions['status'] = Transaction::STATUS_WAITING;
            }

            Transaction::whereIn('id', $transactionPackageStatus['transaction_ids'])->update($dataUpdateTransactions);
        }

        unset($transactionPackageStatus['transaction_ids']);

        $package->update($transactionPackageStatus);

        return response([
            'success' => true,
            'data' => $package,
            'message' => $transactionPackageStatus['reason'],
            'status' => $transactionPackageStatus['status']
        ]);
    }

    public function detail($id) {
        if (empty($id)) {
            return response(Utils::FailedResponse('id is required'));
        }

        $service = ShopService::with(['service'])->where('shop_id', $this->shopNow)
            ->where('service_parent_id', '!=', -1)
            ->where('status', 1)->where('id', $id)->first();

        if (empty($service)) {
            return response(Utils::FailedResponse('Dịch vụ không tìm thấy hoặc đang trong quá trình nâng cấp dịch vụ'));
        }

        return response([
            'success' => true,
            'data' => $service
        ]);
    }
}