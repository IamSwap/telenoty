<?php

namespace App\Console\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class RevokeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'revoke';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke TeleNotyBot from sending you notifications.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $message = 'To revoke TeleNotyBot from sending you notifications either delete your server or your token/username from TeleNoty dashboard.';

        $this->replyWithMessage(['text' => $message]);
    }
}
