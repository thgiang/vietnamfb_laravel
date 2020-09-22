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
}