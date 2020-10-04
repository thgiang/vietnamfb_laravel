<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['api'],
], function ($router) {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
});

// all nguoi dung
Route::group([
    'middleware' => ['api', 'jwt.verify'],
    'namespace' => 'Api'
], function ($router) {
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');

    Route::get('menus', 'HomeController@menus');

    Route::group([
        'prefix' => 'fb'
    ], function ($router) {
        Route::get('personal/subs-count', 'FacebookController@personalSubsCount');
    });

    Route::group([
        'prefix' => 'package'
    ], function ($router) {
        Route::get('detail/{sku}', 'PackageController@detail');
        Route::post('add-order', 'PackageController@addOrder');
        Route::post('cancel-order', 'PackageController@cancelOrder');
        Route::get('list-order', 'PackageController@listOrder');
        Route::get('list-order/{id}/transactions', 'PackageController@listTransactionByOrderId');
    });

    Route::group([
        'prefix' => 'account'
    ], function ($router) {
        Route::get('analytics', 'AccountController@analytics');
    });
});

// la root shop, la thang Trung
Route::group([
    'middleware' => ['api', 'jwt.verify', 'admin'],
    'namespace' => 'Api\Admin',
    'prefix' => 'admin'
], function ($router) {
    Route::post('package/refund/submit', 'PackageController@refundSubmit');
    Route::post('package/process/start', 'PackageController@processStart');
});

// la cac chu web, ke ca thang Trung
Route::group([
    'middleware' => ['api', 'jwt.verify', 'shop.verify'],
    'namespace' => 'Api\Shop',
    'prefix' => 'shop'
], function ($router) {
    Route::get('package/list-order', 'PackageController@listOrder');
    Route::get('account/list-account', 'AccountController@listAccount');
    Route::post('balance/top-up', 'BalanceController@topUp');
});
