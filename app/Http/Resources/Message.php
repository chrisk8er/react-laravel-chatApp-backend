<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userID = auth()->id();

        $images = json_decode($this->images);
        $new_images = [];
        if (isset($images) and count($images)) {
            foreach ($images as $image) {
                array_push($new_images, Storage::url($image));
            }
        }

        $otherUserName = null;

        if ($userID == $this->channel->sender_id) {
            $user = $this->channel->receiver;
        }else {
            $user = $this->channel->sender;
        }

        $otherUserName = title_case($user->name);

        return [
            'created_at'         => $this->created_at,
            'message'            => $this->message,
            'images'             => $new_images,
            'userKey'            => strtoupper(str_split($this->sender->name)[0]),
            'firstKey'           => strtoupper(str_split($user->name)[0]),
            'myFirstKey'         => strtoupper(str_split(auth()->user()->name)[0]),
            'myId'               => auth()->id(),
            'sender_id'          => $this->sender_id,
            'myName'             => title_case(auth()->user()->name),
            'otherUserName'      => $otherUserName,
            'isMe'               => $this->sender_id == $userID ? true: false,
            'channelID'          => $this->channel_id
        ];
    }
}
