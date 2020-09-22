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

        if (! $token = auth()->attempt($credentials)) {
            // kiem tra neu account do la 1 shop thuoc shop current
            unset($credentials['shop_id']);
            $credentials['has_shop_id'] = $this->shopNow;

            if (! $token = auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request) {
        $data = $request->all();

        $checkAccount = Account::where('shop_id', $this->shopNow)->where('email', $data['email'])->count();

        if ($checkAccount > 0) {
            return response([
                'success' => false,
                'message' => 'Email đã tồn tại trong hệ thống'
            ]);
        }

        $account = Account::create([
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
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
