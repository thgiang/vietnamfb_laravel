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
        Route::get('detail/{id}', 'PackageController@detail');
        Route::post('add-order', 'PackageController@addOrder');
    });
});

Route::group([
    'middleware' => ['api', 'jwt.verify', 'admin'],
    'namespace' => 'Api\Admin'
], function ($router) {
    Route::post('refund/submit', 'PackageController@refundSubmit');
});

