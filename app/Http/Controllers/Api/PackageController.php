<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends BaseController
{
    private function _validateAddOrder($serviceSKU, $data) {
        // todo: add logic
        if ($serviceSKU == Service::SKU['FB_BUFF_AUTO']) {
            return $this->_validateFbBuffAuto($data);
        }
    }

    private function _validateFbBuffAuto($data) {
        // todo: add logic
    }

    public function addOrder(Request $request) {
        $data = $request->all();

        $validate = $this->_validateAddOrder($data['service_sku'], $data);

        if (!empty($validate)) {
            return response([
                'success' => false,
                'message' => $validate
            ]);
        }

        //todo: add logic
    }
}
