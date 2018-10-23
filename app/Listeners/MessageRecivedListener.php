<?php

namespace App\Listeners;

use App\Events\MessageRecived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageRecivedListener
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
     * @param  MessageRecived  $event
     * @return void
     */
    public function handle(MessageRecived $event)
    {
        //
    }
}
