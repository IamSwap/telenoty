<?php

namespace App\Http\Controllers;

use Token;
use Telegram;
use App\Server;
use App\Receiver;
use Illuminate\Http\Request;

class ReceiverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Server $server, Request $request)
    {
        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'Unauthorized Request!'
            ], 403);
        }

        return $server->receivers()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Server $server
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Server $server, Request $request)
    {
        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'Unauthorized Request!'
            ], 403);
        }

        return $server->receivers()->create([
            'token' => Token::unique('receivers', 'token', 16),
            'status' => 'inactive'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Server $server
     * @param  Receiver  $receiver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server, Receiver $receiver)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'Unauthorized Request!'
            ], 403);
        }

        if ($server->id != $receiver->server_id) {
            return response()->json([
                'message' => 'Bad Request!'
            ], 403);
        }

        if ($request->input('status') == 'active' && ! $receiver->chat_id) {
            return response()->json([ 'message' => 'This receiver can not be activated! Make sure that, it is authorized by a Telegram user first.' ], 400);
        }

        $receiver->update([
            'status' => ($request->input('status') == 'active') ? 'active' : 'inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Server $server
     * @param Receiver $receiver
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Receiver $receiver, Request $request)
    {
        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'Unauthorized Request!'
            ], 403);
        }

        if ($server->id != $receiver->server_id) {
            return response()->json([
                'message' => 'Bad Request!'
            ], 403);
        }

        $receiver->delete();
    }

    /**
     * Authorize the specified receiver in storage.
     *
     * @param  Server $server
     * @param  Receiver  $receiver
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activate(Server $server, Receiver $receiver, Request $request)
    {
        $this->validate($request, [
            'chat_id' => 'required',
            'name' => 'required',
            'username' => 'required'
        ]);

        if ($request->user()->id != $server->user_id) {
            return response()->json([
                'message' => 'Unauthorized Request!'
            ], 403);
        }

        if ($server->id != $receiver->server_id) {
            return response()->json([
                'message' => 'Bad Request!'
            ], 403);
        }

        $receiver->update([
            'chat_id' => $request->input('chat_id'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'status' => 'active'
        ]);

        $message = '🎉 Successfully authorized Server *'.$server->title.'* to @TeleNotyBot. You are now able to receive Telegram notification for this server.';

        Telegram::sendMessage([
            'chat_id' => $receiver->chat_id,
            'text' => $message,
            'parse_mode' => 'markdown'
        ]);
    }
}
