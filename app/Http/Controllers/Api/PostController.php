<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->limit = 10;
    }

    public function list(Request $request) {
        $page = $request->input('page', 1);
        $skip = ($page - 1) * $this->limit;

        $posts = Post::with('account')->where('shop_id', $this->shopNow)->where('status', 1)
            ->orderBy('id', 'desc')->skip($skip)->limit($this->limit)->get();

        foreach ($posts as &$post) {
            $post->last_comment = Comment::with(['account', 'replies' => function($q) {
                return $q->with('account');
            }])
                ->where('post_id', $post->id)->where('parent_id', -1)->orderBy('id', 'desc')->first();
        }

        return response([
            'success' => true,
            'data' => $posts
        ]);
    }
}
