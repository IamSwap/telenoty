<?php

namespace App\Events;

use App\Subscriber;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AuthorizeSubscriber implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subscriber;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subscriber, $data)
    {
        $this->subscriber = $subscriber;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if (is_a($this->subscriber, 'App\Subscriber')) {
            return new PrivateChannel('App.User.'.$this->subscriber->user_id);
        }

        if (is_a($this->subscriber, 'App\ProjectSubscriber')) {
            return new PrivateChannel('App.User.'.$this->subscriber->project->user_id);
        }
    }
}
