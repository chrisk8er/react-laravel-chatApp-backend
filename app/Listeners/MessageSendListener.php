<?php

namespace App\Listeners;

use App\Events\MessageSend;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageSendListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSend  $event
     * @return void
     */
    public function handle(MessageSend $event)
    {
        //
    }
}
