<?php

namespace App\Console\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'How to use TeleNoty Notification Bot';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $message = '
            Hello, ' .$this->getFromName(). '. Welcome to TeleNoty bot! 🤖 🎉

This bot is used to receive *Laravel Forge\'s* deployment notifications through Telegram.

Before we start, please make sure you are currently inside [TeleNoty dashboard](https://telenoty.com/dashboard).

To start, type this command:
/authorize _<server-token>_';

        $this->replyWithMessage(['text' => $message, 'parse_mode' => 'markdown']);
    }

    /**
     * Get from name
     *
     * @return string
     */
    private function getFromName()
    {
        return $this->update['message']['from']['first_name'] . ' ' . $this->update['message']['from']['last_name'];
    }
}
