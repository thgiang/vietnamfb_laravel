<?php

namespace App\Http\Controllers\Api\Shop;

use App\Helpers\Utils;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PostController extends BaseController
{
    public function create(CreatePostRequest $request) {
        $data = $request->all();

        if ($request->file('image') && $request->file('image')->isValid()) {
            $folder = 'posts';
            $nameImage = 'bai-dang-'.time();
            $data['image'] = $this->_saveImage($request->file('image'), $folder, $nameImage);
        }

        $data['status'] = !empty($data['status']) and $data['status'] == 'on' ? 1 : 0;
        $data['account_id'] = auth()->user()->id;
        $data['type'] = auth()->user()->is_root;
        $data['shop_id'] = $this->shopNow;

        try {
            $post = Post::create($data);
        } catch (\Exception $ex) {
            return response(Utils::FailedResponse('Lỗi hệ thống, vui lòng liên hệ admin để được hướng dẫn'));
        }

        return response([
            'success' => true,
            'data' => $post,
            'message' => 'Đã tạo post thành công'
        ]);
    }

    public function edit($id) {
        $post = new Post();

        if (auth()->user()->is_root == 0) {
            $post = $post->where('shop_id', $this->shopNow);
        }

        $post = $post->where('id', $id)->first();

        if (empty($post)) {
            return response(Utils::FailedResponse('Không tìm thấy bài đăng này'));
        }

        return response([
            'success' => true,
            'data' => $post
        ]);
    }

    public function update($id, CreatePostRequest $request) {
        $data = $request->all();
        $post = Post::where('shop_id', $this->shopNow)->where('id', $id)->first();

        if (empty($post)) {
            return response(Utils::FailedResponse('Không tìm thấy bài đăng này'));
        }

        $data['status'] = !empty($data['status']) and $data['status'] == 'on' ? 1 : 0;
        if ($request->file('image') && $request->file('image')->isValid()) {
            $folder = 'posts';
            $nameImage = 'bai-dang-'.time();
            $data['image'] = $this->_saveImage($request->file('image'), $folder, $nameImage);
        } else if (!empty($data['image'])) {
            unset($data['image']);
        }

        try {
            $post->update($data);
        } catch (\Exception $ex) {
            Log::info(__LINE__ . $ex->getMessage());
            return response(Utils::FailedResponse('Lỗi hệ thống, vui lòng liên hệ admin để được hướng dẫn'));
        }

        return response([
            'success' => true,
            'data' => $post,
            'message' => 'Đã cập nhật post thành công'
        ]);
    }

    public function delete($id) {
        $post = new Post();

        if (auth()->user()->is_root == 0) {
            $post = $post->where('shop_id', $this->shopNow);
        }

        $post = $post->where('id', $id)->first();

        if (empty($post)) {
            return response(Utils::FailedResponse('Không tìm thấy bài đăng này'));
        }

        $post->delete();

        return response([
            'success' => true,
            'message' => 'Xóa bài đăng thành công'
        ]);
    }

    public function listPost(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $posts = Post::with(['account', 'shop']);

        if (auth()->user()->is_root == 0) {
            $posts = $posts->where('shop_id', $this->shopNow);
        }

        $posts = $posts->orderBy('id', 'desc')->skip($skip)->limit($this->limit)->get();

        return response([
            'success' => true,
            'data' => $posts
        ]);
    }
}
