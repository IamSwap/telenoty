<?php

namespace App\Http\Controllers;

use Token;
use Telegram;
use App\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        return $request->user()->subscribers()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->user()->subscribers()->create([
            'token' => Token::unique('subscribers', 'token', 16),
            'status' => 'inactive'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Subscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        if ($request->input('status') == 'active' && ! $subscriber->chat_id) {
            return response()->json([ 'message' => 'This subscriber can not be activated! Make sure that, it is authorized by a Telegram user first.' ], 400);
        }

        $subscriber->update([
            'status' => ($request->input('status') == 'active') ? 'active' : 'inactive'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Subscriber $subscriber
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber, Request $request)
    {
        $subscriber->delete();
    }

    /**
     * Authorize the specified subscriber in storage.
     *
     * @param  Subscriber $subscriber
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activate(Subscriber $subscriber, Request $request)
    {
        $this->validate($request, [
            'chat_id' => 'required',
            'name' => 'required',
            'username' => 'required'
        ]);

        $subscriber->update([
            'chat_id' => $request->input('chat_id'),
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'status' => 'active'
        ]);

        $message = '🎉 Successfully authorized to @TeleNotyBot. You are now able to receive Telegram deployment notifications.';

        Telegram::sendMessage([
            'chat_id' => $subscriber->chat_id,
            'text' => $message,
            'parse_mode' => 'markdown'
        ]);
    }
}
