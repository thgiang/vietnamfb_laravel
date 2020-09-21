<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Fb\PersonalSubsCountRequest;
use App\Services\FBService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacebookController extends BaseController
{
    public function personalSubsCount(PersonalSubsCountRequest $request) {
        $fid = trim($request->input('fid'));

        $fbService = new FBService();

        $res = $fbService->getSubsCountByFid($fid);

        return $res;
    }
}
