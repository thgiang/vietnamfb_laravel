<?php

namespace App\Http\Controllers\Api;

use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected $shopNow;
    protected $limit;
    protected $page;

    public function __construct(Request $request)
    {
        $this->shopNow = $request->header('shopId');
        $this->limit = config('constants.pagination.limit', 20);
    }

    protected function _saveImage($file, $folder = 'posts', $name) {
        $filename = (!empty($name) ? $name : str_slug($file->getClientOriginalName())).'-' .md5(time()) . '.' . $file->getClientOriginalExtension();

        if (!is_dir(public_path('/files/'. $folder))) {
            mkdir(public_path('/files/'. $folder));
        }

        $subPath = Carbon::now()->format('Ymd');
        $savePath = '/files/'. $folder.'/'.$subPath;

        if (!is_dir(public_path($savePath))) {
            mkdir(public_path($savePath));
        }

        $filename = $savePath . '/' . $filename;

        try {
            move_uploaded_file($file, public_path() . $filename);
        } catch (\Exception $ex) {
            return false;
        }

        return $filename;
    }
}
