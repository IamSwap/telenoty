<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::post('/notify/{token}', 'NotificationController@notify');

Route::post(config('telegram.bot_token') . '/webhook', function () {
    $update = Telegram::commandsHandler(true);
    $updates = Telegram::getWebhookUpdates();

    return 'ok';
});

Auth::routes();

Route::get('/dashboard/{vue_router?}', 'DashboardController@index')->where('vue_router', '[\/\w\.-]*');
