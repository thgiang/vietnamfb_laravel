<?php
namespace App\Services;

use App\Models\Setting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FBService
{
    protected $client;
    protected $headers;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://graph.facebook.com',
            'timeout' => 30,
            'verify' => false,
            'http_errors' => false
        ]);
    }

    protected function _doRequest($method, $url, $params = []) {
        try {
            $response = $this->client->request($method, $url, $params);

            $body = $response->getBody();

            $remainingBytes = $body->getContents();

            $returnBody = json_decode($remainingBytes, true);

            return $returnBody;
        } catch (\Exception $ex) {
            Log::info('FBService/_doRequest:' . $ex->getMessage());
            return [
                'error' => $ex->getMessage()
            ];
        }
    }

    public function getSubsCountByFid($fid) {
        $setting = Setting::where('type', Setting::TYPE_TOKEN)->where('status', 1)->first();

        if (empty($setting)) {
            return [
                'success' => false,
                'message' => 'Valid token not found in settings'
            ];
        }

        $url = '/' . $fid . '/?fields=id,name,subscribers&access_token=' . $setting->setting;

        $res = $this->_doRequest('GET', $url);

        if (!empty($res['error'])) {
            return [
                'success' => false,
                'message' => $res['error']
            ];
        }

        $countSubs = !empty($res['subscribers']['summary']['total_count']) ? $res['subscribers']['summary']['total_count'] : -1;

        return [
            'success' => true,
            'data' => $countSubs
        ];
    }
}