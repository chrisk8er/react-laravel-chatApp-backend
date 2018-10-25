<?php

namespace App\Broadcasting;

use App\Model\Channel;
use App\User;
class Chat
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, Channel $channel)
    {

        $userIds = [$channel->sender_id, $channel->receiver_id];
        if (in_array($user->id, $userIds)) {
            return true;
        }

        return false;
    }
}
