<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('live.{roomId}', function ($user, $roomId) {
    return ['id' => $user->id, 'name' => $user->name];
});


Broadcast::channel('chatRoom.{roomId}', function ($user, $roomId) {
    if ($user->isSameChannel($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});