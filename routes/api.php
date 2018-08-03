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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('/servers', 'ServerController');
    Route::resource('/servers/{server}/receivers', 'ReceiverController');
    Route::post('/servers/{server}/receivers/{receiver}/authorize', 'ReceiverController@activate');

    Route::post('/profile', 'SettingsController@profile');
    Route::post('/profile/password', 'SettingsController@password');
});
