<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Requests\CreateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class SupportController extends BaseController
{
    public function count(Request $request) {
        $waiting = Ticket::where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)->where('status', Ticket::STATUS_WAITING)->count();
        $doing = Ticket::where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)->where('status', Ticket::STATUS_DOING)->count();
        $done = Ticket::where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)->where('status', Ticket::STATUS_DONE)->count();

        return response([
            'success' => true,
            'data' => [
                'waiting' => $waiting,
                'doing' => $doing,
                'done' => $done
            ]
        ]);
    }

    public function list(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $tickets = Ticket::withCount('comments')->where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)
            ->skip($skip)->limit($this->limit)->orderBy('id', 'desc')->get();

        return response([
            'success' => true,
            'data' => $tickets
        ]);
    }

    public function create(CreateTicketRequest $request) {
        $data = $request->all();

        if ($request->file('image') && $request->file('image')->isValid()) {
            $folder = 'tickets';
            $nameImage = 'ho-tro-'.time();
            $data['image'] = $this->_saveImage($request->file('image'), $folder, $nameImage);
        }

        $data['shop_id'] = $this->shopNow;
        $data['account_id'] = auth()->user()->id;
        $data['status'] = Ticket::STATUS_WAITING;

        try {
            $ticket = Ticket::create($data);
        } catch (\Exception $ex) {
            Log::info(__LINE__.$ex->getMessage());
            return response(Utils::FailedResponse('Lỗi hệ thống, vui lòng liên hệ admin để được hướng dẫn'));
        }

        return response([
            'success' => true,
            'data' => $ticket,
            'message' => 'Tạo ticket hỗ trợ thành công'
        ]);
    }
}
