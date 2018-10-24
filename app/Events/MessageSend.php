<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message, $channelID;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $channelID)
    {
        $this->message    = $message;
        $this->channelID   = $channelID;
    }

    public function broadcastWith()
    {
        return ['data' => $this->message];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chatRoom.'.$this->channelID);
    }
}
