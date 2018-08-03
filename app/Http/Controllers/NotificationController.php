<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Notify user about deployments
     *
     * @param string $token
     * @param Request $request
     * @return void
     */
    public function notify($token, Request $request)
    {
        /* {"status":"success","server":{"id":221222,"name":"telenoty"},"site":{"id":587400,"name":"telenoty.com"},"commit_hash":"105fc0ab3179d07f3d706a23f3ec615ae948b131","commit_url":"https:\/\/github.com\/IamSwap\/telenoty\/commit\/105fc0ab3179d07f3d706a23f3ec615ae948b131","commit_author":"Swapnil Bhavsar","commit_message":"Added system models & setup"} */

        Telegram::sendMessage([
            'chat_id' => $token,
            'text' => json_encode($request->all())
        ]);
    }
}
