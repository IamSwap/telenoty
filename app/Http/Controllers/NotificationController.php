<?php

namespace App\Http\Controllers;

use App\User;
use Telegram;
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
        $message = $this->parseMessage($request->all());

        $user = User::where('webhook_token', $token)->first();

        if ($user) {
            $subscribers = $user->subscribers()->where('status', 'active')->get();

            foreach ($subscribers as $subscriber) {
                Telegram::sendMessage([
                    'chat_id' => $subscriber->chat_id,
                    'text' => $message,
                    'parse_mode' => 'markdown'
                ]);
            }
        }
    }

    /**
     * Parse message;
     *
     * @param array $data
     * @return void
     */
    private function parseMessage(array $data)
    {
        if ($data['status'] == 'success') {
            return '✅ '. $data['commit_author'] .' deployed some fresh code!

*Server*: '.$data['server']['name'].'
*Site*: ['.$data['site']['name'].']('.$data['site']['name'].')
*Commit*: ['.$data['commit_url'].']('.$data['commit_url'].')
*Message*: '.$data['commit_message'];
        }

        return '❌ Something went wrong during deployment!

*Server*: '.$data['server']['name'].'
*Site*: ['.$data['site']['name'].']('.$data['site']['name'].')
*Commit*: ['.$data['commit_url'].']('.$data['commit_url'].')
*Message*: '.$data['commit_message'];
    }
}
