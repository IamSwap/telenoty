<?php

use Illuminate\Http\Request;

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

//Telegram::setWebhook(['url' => config('app.url') . '/<token>/webhook']);

Route::get('/', function () {
    //return Telegram::getUpdates();
});

Route::post('/notify/{token}', function ($token, Request $request) {
    \Log::info($request->all());
    $status = $request->input('status');

    Telegram::sendMessage([
        'chat_id' => $token,
        'text' => json_encode($request->all())
    ]);
});

Route::post('/<token>/webhook', function () {
    $update = Telegram::commandsHandler(true);
    $updates = Telegram::getWebhookUpdates();

    \Log::info(request()->all());

    // Commands handler method returns an Update object.
    // So you can further process $update object
    // to however you want.

    return 'ok';
});

// test
// Telegram::setWebhook(['url' => 'https://telenoty.com/<token>/webhook']);
