<?php

namespace App\Console\Commands;

use App\Subscriber;
use Telegram\Bot\Commands\Command;
use App\Events\AuthorizeSubscriber;

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
            $subscriber = Subscriber::where('token', $arguments)->first();

            if ($subscriber) {
                if (! $subscriber->chat_id) {
                    if (! $subscriber->user->subscribers()->where('chat_id', $from['id'])->exists()) {
                        $message = '✅ A popup will appear inside TeleNoty dashboard, please click *Authorize* button to approve authorization.';

                        event(new AuthorizeSubscriber($subscriber, $from));
                    } else {
                        $message = '😥 You are already authorized with *TeleNotyBot*. Please delete existing token/subscriber from TeleNoty dashboard & generate new token to attempt another authorization.';
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
