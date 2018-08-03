<?php

namespace App\Console\Commands;

use App\Receiver;
use App\Events\AuthorizeReceiver;
use Telegram\Bot\Commands\Command;

class AuthorizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'authorize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authorize your server to send notification.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $from = $this->update['message']['from'];

        if (! $arguments) {
            $message = '💔 You must provide _<server-token>_ to attempt authorization.

Eg.: /authorize _<server-token>_';
        } else {
            $receiver = Receiver::where('token', $arguments)->first();

            if ($receiver) {
                if (! $receiver->chat_id) {
                    if (! $receiver->server->receivers()->where('chat_id', $from['id'])->exists()) {
                        $message = '✅ A popup will appear inside TeleNoty dashboard, please click *Authorize* button to approve autorization.';

                        event(new AuthorizeReceiver($receiver, $from));
                    } else {
                        $message = '😥 You are already authorized with *TeleNotyBot*. Please delete existing token/receiver from TeleNoty dashboard & generate new token to attempt another authorization.';
                    }
                } else {
                    $message = '😰 This token is aleardy used for authorization with *TeleNotyBot*. Please generate new token from TeleNoty dashboard to attempt another authorization.';
                }
            } else {
                $message = '😬 Sorry, token *' . $token . '* cannot be found inside our database.';
            }
        }

        $this->replyWithMessage(['text' => $message, 'parse_mode' => 'markdown']);
    }
}
