<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        $credentials['shop_id'] = $this->shopNow;

        if (!$token = auth()->attempt($credentials)) {
            // kiem tra neu account do la 1 shop thuoc shop current
            unset($credentials['shop_id']);
            $credentials['has_shop_id'] = $this->shopNow;

            if (!$token = auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản hoặc mật khẩu không đúng'
                ], 401);
            }
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        $checkAccount = Account::where('shop_id', $this->shopNow)->where(function ($query) use ($data) {
            $query->where('email', $data['email'])->orWhere('tel', $data['tel']);
        })->first();

        if ($checkAccount) {
            $existingMessage = '';
            if ($checkAccount->tel === $data['tel']) {
                if ($checkAccount->email === $data['email']) {
                    $existingMessage = 'Số điện thoại và email này đã tồn tại trong hệ thống';
                } else {
                    $existingMessage = 'Số điện thoại này đã tồn tại trong hệ thống';
                }
            } else {
                $existingMessage = 'Email này đã tồn tại trong hệ thống';
            }
            return response([
                'success' => false,
                'message' => $existingMessage
            ]);
        }

        $account = Account::create([
            'fullname' => $data['fullname'],
            'tel' => $data['tel'],
            'username' => $data['tel'], // Username dùng số đt luôn
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_root' => 0,
            'shop_id' => $this->shopNow
        ]);

        return response([
            'success' => true,
            'message' => 'Đăng ký tài khoản thành công',
            'data' => $account
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $account = Account::with('shop')->where('shop_id', $this->shopNow)->where('id', auth()->user()->id)->first();

        if (empty($account)) {
            $account = Account::with('shop')->where('has_shop_id', $this->shopNow)->where('id', auth()->user()->id)->first();
        }

        if ($account->has_shop_id == $this->shopNow) {
            if ($account->is_root == 1) {
                $account->role = 'super';
            } else {
                $account->role = 'admin';
            }
        } else {
            $account->role = 'user';
        }

        return response()->json([
            'success' => true,
            'data' => $account
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }
}
