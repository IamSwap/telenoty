<?php

namespace App\Http\Controllers;

use App\Server;
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

        $server = Server::where('token', $token)->first();

        if ($server && $server->status == 'active') {
            $receivers = $server->receivers()->where('status', 'active')->get();

            foreach ($receivers as $receiver) {
                Telegram::sendMessage([
                    'chat_id' => $receiver->chat_id,
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

        return '❌ '. $data['commit_author'] .' deployed some fresh code!

*Server*: '.$data['server']['name'].'
*Site*: ['.$data['site']['name'].']('.$data['site']['name'].')
*Commit*: ['.$data['commit_url'].']('.$data['commit_url'].')
*Message*: '.$data['commit_message'];
    }
}
