<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Requests\CancelOrderRequest;
use App\Models\Account;
use App\Models\Package;
use App\Models\Service;
use App\Models\Shop;
use App\Models\ShopService;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class PackageController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    private function _validateAddOrder($data) {
        // todo: add logic
        if (empty($data['service_type'])) {
            return 'Thiếu loại dịch vụ: `service_type`';
        }

        if ($data['service_type'] == Service::FB_BUFF_SUB) {
            return $this->_validateFbBuffSub($data);
        }

        return 'Không tồn tại dịch vụ này';
    }

    private function _validateFbBuffSub($data) {
        if (empty($data['shop_service_id'])) {
            return 'Thiếu id dịch vụ: `shop_service_id`';
        }

        if (!in_array($data['service_type'], [Service::FB_BUFF_SUB])) {
            return 'Không tồn tại loại dịch vụ này';
        }

        if (empty($data['quantity']) || $data['quantity'] <= 0) {
            return 'Số lượng mua phải lớn hơn 0: `quantity`';
        }

        return '';
    }

    public function addOrder(Request $request) {
        $data = $request->all();

        $validate = $this->_validateAddOrder($data);

        if (!empty($validate)) {
            return response(Utils::FailedResponse($validate));
        }

        $shopService = ShopService::where('shop_id', $this->shopNow)->where('status', 1)->where('id', $data['shop_service_id'])->first();

        if (empty($shopService)) {
            return response(Utils::FailedResponse('Dịch vụ không tìm thấy hoặc đang trong quá trình nâng cấp dịch vụ'));
        }

        // tinh amount
        $amount = $shopService->price * intval($data['quantity']);

        $code = Utils::genCodePackage($this->shopNow, $data['shop_service_id']);

        // tao don
        $package = Package::create([
            'shop_id' => $this->shopNow,
            'code' => $code,
            'account_id' => auth()->user()->id,
            'shop_service_id' => $data['shop_service_id'],
            'type' => $data['service_type'],
            'status' => Package::STATUS_ORDER_DOING,
            'amount' => $amount,
            'quantity' => $data['quantity'],
            'package_json' => json_encode($data)
        ]);

        // tao cac transactions
        $transactionService = new TransactionService();

        $transactionPackageStatus = $transactionService->makeTransactionPiplineForPackage($package->id);

        if (!empty($transactionPackageStatus['transaction_ids']) and count($transactionPackageStatus['transaction_ids']) > 0) {

            $transactionService->updateTransactionsStatus($transactionPackageStatus['transaction_ids'],
                $transactionPackageStatus['status'], $transactionPackageStatus['reason']);

        }

        $dataPackageUpdate = [
            'status' => $transactionPackageStatus['status'],
            'reason' => $transactionPackageStatus['reason']
        ];

        if ($transactionPackageStatus['status'] == Package::STATUS_ORDER_SUCCESS) {
            $dataPackageUpdate['status_process'] = Package::STATUS_PROCESS_WAIT;
        }

        $package->update($dataPackageUpdate);

        return response([
            'success' => true,
            'data' => $package,
            'message' => $transactionPackageStatus['reason'],
            'status' => $transactionPackageStatus['status']
        ]);
    }

    public function detail($sku) {
        if (empty($sku)) {
            return response(Utils::FailedResponse('sku is required'));
        }

        $service = ShopService::with(['service' => function($q) use($sku) {
            return $q->where('sku', $sku);
        }])->where('shop_id', $this->shopNow)
            ->where('service_parent_id', '!=', -1)
            ->where('status', 1)->first();

        if (empty($service)) {
            return response(Utils::FailedResponse('Dịch vụ không tìm thấy hoặc đang trong quá trình nâng cấp dịch vụ'));
        }

        return response([
            'success' => true,
            'data' => $service
        ]);
    }

    public function cancelOrder(CancelOrderRequest $request) {
        $packageId = $request->input('package_id');

        $package = Package::where('shop_id', $this->shopNow)->where('id', $packageId)->first();

        if ($package->status_process == Package::STATUS_PROCESS_SUCCESS) {
            return response(Utils::FailedResponse('Đơn này đã thực hiện thành công, không thể hủy đơn'));
        }

        $package->update([
            'status_process' => Package::STATUS_PROCESS_CANCEL,
            'status' => auth()->user()->has_shop_id != -1 ? Package::STATUS_ADMIN_CANCEL : Package::STATUS_CUSTOMER_CANCEL
        ]);

        return response([
            'success' => true,
            'message' => 'Đã hủy đơn thành công. Nếu dịch vụ đang chạy dở, bạn sẽ được hoàn lại tiền phần còn lại tương ứng'
        ]);
    }

    public function listOrder(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $packages = Package::with(['shopService' => function($q) {
            return $q->with('service');
        }])
            ->where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)->orderBy('id', 'desc')->skip($skip)->limit($this->limit)->get();

        $total = Package::where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)->count();

        return response([
            'success' => true,
            'data' => $packages,
            'total' => $total
        ]);
    }

    public function listTransactionByOrderId($id, Request $request) {
        $package = Package::where('shop_id', $this->shopNow)->where('id', $id)->where('account_id', auth()->user()->id)->first();

        if (empty($package)) {
            return response(Utils::FailedResponse('Không tìm thấy đơn hàng này'));
        }

        $transactions = Transaction::where('package_id', $package->id)->where('from_account_id', auth()->user()->id)->get();

        $total = Transaction::where('package_id', $package->id)->where('from_account_id', auth()->user()->id)->count();

        return response([
            'success' => true,
            'data' => $transactions,
            'total' => $total
        ]);
    }
}
