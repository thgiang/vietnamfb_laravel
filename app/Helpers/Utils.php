<?php
/**
 * Created by PhpStorm.
 * User: vohuy
 * Date: 9/22/2020
 * Time: 9:06 PM
 */

namespace App\Helpers;


class Utils
{
    public static function FailedResponse($message) {
        return [
            'success' => false,
            'message' => $message
        ];
    }

    public static function genCodePackage($shopId, $serviceId) {
        return 'S' . $shopId . '.SV' . $serviceId . '.' . time() . '.' . mt_rand(0, 100);
    }
}