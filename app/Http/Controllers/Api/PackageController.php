<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use App\Models\ShopService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends BaseController
{
    private function _validateAddOrder($serviceType, $data) {
        // todo: add logic
        if ($serviceType == Service::FB_BUFF_SUB) {
            return $this->_validateFbBuffSub($data);
        }
    }

    private function _validateFbBuffSub($data) {
        // todo: add logic
        return '';
    }

    public function addOrder(Request $request) {
        $data = $request->all();

        $validate = $this->_validateAddOrder($data['service_type'], $data);

        if (!empty($validate)) {
            return response([
                'success' => false,
                'message' => $validate
            ]);
        }

        //todo: add logic
    }

    public function detail($id) {
        if (empty($id)) {
            return response([
                'success' => false,
                'message' => 'id is required'
            ]);
        }

        $service = ShopService::with(['service'])->where('shop_id', $this->shopNow)
            ->where('service_parent_id', '!=', -1)
            ->where('status', 1)->where('id', $id)->first();

        if (empty($service)) {
            return response([
                'success' => false,
                'message' => 'Service not found'
            ]);
        }

        return response([
            'success' => true,
            'data' => $service
        ]);
    }
}
