<?php

namespace App\Console\Commands;

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
        if (! $arguments) {
            $message = '💔 You must provide _<server-token>_ to attempt authorization.

Eg.: /authorize _<server-token>_';
        } else {
            $message = 'A popup will appear inside Telenoty dashboard, please click *Authorize* button to approve autorization.';
        }

        $this->replyWithMessage(['text' => $message, 'parse_mode' => 'markdown']);
    }
}
