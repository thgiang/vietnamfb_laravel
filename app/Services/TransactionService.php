<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Package;
use App\Models\Shop;
use App\Models\ShopService;
use App\Models\Transaction;

class TransactionService
{
    public function makeTransactionPiplineForPackage($packageId) {
        $package = Package::find($packageId);
        if (empty($package)) {
            return [
                'status' => Package::STATUS_FAIL,
                'reason' => 'Không tìm thấy đơn hàng ID='.$packageId,
                'transaction_ids' => []
            ];
        }

        $shop = Shop::find($package->shop_id);
        if (empty($shop)) {
            return [
                'status' => Package::STATUS_FAIL,
                'reason' => 'Không tìm thấy shop ID=' . $package->shop_id,
                'transaction_ids' => []
            ];
        }

        $shopService = ShopService::find($package->shop_service_id);
        if (empty($shopService)) {
            return [
                'status' => Package::STATUS_FAIL,
                'reason' => 'Không tìm thấy dịch vụ ID='.$shopService->id,
                'transaction_ids' => []
            ];
        }

        $quantity = $package->quantity;
        $fromAccountId = $package->account_id;

        $transactionIds = [];

        while (true) {
            $toAccountId = $shop->account->id;

            $transaction = Transaction::create([
                'package_id' => $packageId,
                'shop_id' => $shop->id,
                'shop_service_id' => $shopService->id,
                'amount' => ($shopService->price * $quantity),
                'quantity' => $quantity,
                'from_account_id' => $fromAccountId,
                'to_account_id' => $toAccountId,
                'type' => Transaction::TYPE_NEW_ORDER,
                'status' => Transaction::STATUS_DOING
            ]);

            $transactionIds[] = $transaction->id;

            $account = Account::find($transaction->from_account_id);
            if (empty($account)) {
                return [
                    'status' => Package::STATUS_FAIL,
                    'reason' => 'Không tìm thấy tài khoản ID='.$transaction->from_account_id,
                    'transaction_ids' => $transactionIds
                ];
            } else if ($account->is_root == 0 && $account->balance < $transaction->amount) {
                $statusFinalPackage = Package::STATUS_WAITING;
                if ($account->id == $package->account_id) {
                    $statusFinalPackage = Package::STATUS_FAIL;
                }

                return [
                    'status' => $statusFinalPackage,
                    'reason' => 'Tài khoản '.$account->username.' (ID='.$account->id.') không đủ tiền, vui lòng nạp tiền và thử lại.',
                    'transaction_ids' => $transactionIds
                ];
            }

            if ($shop->ref_id == -1) {
                return [
                    'status' => Package::STATUS_SUCCESS,
                    'reason' => 'Đặt hàng thành công',
                    'transaction_ids' => $transactionIds
                ];
            }

            $shop = Shop::find($shop->ref_id);
            if (empty($shop)) {
                return [
                    'status' => Package::STATUS_FAIL,
                    'reason' => 'Không tìm thấy ref shop ID=' . $package->shop_id,
                    'transaction_ids' => $transactionIds
                ];
            }

            $shopService = ShopService::where('status', 1)->where('shop_id', $shop->id)->where('service_id', $shopService->service_id)->first();

            if (empty($shopService)) {
                return [
                    'status' => Package::STATUS_FAIL,
                    'reason' => 'Không tìm thấy ref dịch vụ ID=' . $shopService->id,
                    'transaction_ids' => $transactionIds
                ];
            }

            $fromAccountId = $toAccountId;
            $toAccountId = $shop->account->id;
        }

        return [
            'status' => Package::STATUS_FAIL,
            'reason' => 'Lỗi không xác định, vui lòng liên hệ với admin để được giải quyết',
            'transaction_ids' => []
        ];
    }

    public function updateTransactionsStatus($ids, $status, $reason) {
        $dataUpdateTransactions['reason'] = $reason;

        if ($status == Package::STATUS_FAIL) {
            $dataUpdateTransactions['status'] = Transaction::STATUS_FAIL;
        } else if ($status == Package::STATUS_SUCCESS) {
            $dataUpdateTransactions['status'] = Transaction::STATUS_SUCCESS;

            // tru tien cua cac account
            foreach ($ids as $transaction_id) {
                $eachTransaction = Transaction::find($transaction_id);

                $account = Account::find($eachTransaction->from_account_id);
                $account->update([
                    'balance' => $account->balance - $eachTransaction->amount
                ]);
            }
        } else if ($status == Package::STATUS_WAITING) {
            $dataUpdateTransactions['status'] = Transaction::STATUS_WAITING;
        }

        Transaction::whereIn('id', $ids)->update($dataUpdateTransactions);

        return;
    }
}
