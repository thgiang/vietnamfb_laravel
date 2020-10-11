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

    public static function convertSlug($string)
    {
        $string = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẵ|ẳ)/", 'a', $string);
        $string = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $string);
        $string = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $string);
        $string = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $string);
        $string = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $string);
        $string = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $string);
        $string = preg_replace("/(đ)/", 'd', $string);
        $string = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $string);
        $string = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $string);
        $string = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $string);
        $string = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $string);
        $string = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $string);
        $string = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $string);
        $string = preg_replace("/(Đ)/", 'D', $string);
        $string = strtolower(trim($string));
        $string = preg_replace('/[^%a-zA-Z0-9]/', ' ', $string);
        $string = preg_replace('/\s+/', '-', $string);
        $string = preg_replace('|-+|', '-', $string);
        $string = str_replace('%', '', $string);
        $parsed = trim($string, '-');

        return $parsed;
    }
}