<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $tickets = Ticket::where('shop_id', $this->shopNow)->where('account_id', auth()->user()->id)
            ->skip($skip)->limit($this->limit)->orderBy('id', 'desc')->get();

        return response([
            'success' => true,
            'data' => $tickets
        ]);
    }
}
