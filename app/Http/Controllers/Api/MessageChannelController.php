<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Message;
use App\Model\Channel;

use App\Http\Resources\MessageCollection;
use App\Http\Resources\ChannelCollection;
use App\Events\MessageSend;
use App\Events\MessageRecived;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\Channel as ChannelResource;

use App\User;
use Validator;

class MessageChannelController extends Controller
{
    public function chat_list()
    {

        $channels = Channel::where('sender_id', auth()->id())->orWhere('receiver_id',auth()->id())->get();

        $array_channelsIds = [];
        foreach ($channels as $key => $channel) {
            array_push($array_channelsIds, $channel->id);
        }

        $messages = Message::whereIn('channel_id', $array_channelsIds)
                            ->with(['channel.sender','channel.receiver', 'sender'])
                            ->latest()->get();

        $newMessages = new MessageCollection($messages);

        $newMesssssages = $newMessages->groupBy('channelID');

        $allmessages = [];

        foreach ($newMesssssages as $key => $allmessage) {
            $allmessages[] = $allmessage[0];
        }

        return response()->json(['data'=> $allmessages],200);

    }

    public function messages($channelId)
    {

        $messages = Message::where('channel_id', $chatRoom->id)
                            ->with(['channel.sender','channel.receiver', 'sender'])
                            ->get();

        return new MessageCollection($messages);
    }

    public function send_message(Request $r, $channelId)
    {
        $validator = Validator::make($r->all(),[
            'userID'           => 'required',
            'sendNotification' => 'required|boolean'
        ]);

        if ($validator->fails()) {

            return response()->json(['errors'=>$validator->errors()],400);

        }

        if (!$r->message and count($r->images) == 0) {
            return response()->json(['message'=> 'no message or images found'],400);
        }

        $images_url = [];

        if (isset($r->images) and count($r->images)) {
            $images_url =  $this->storeImage($r->images);
        }

        $imagesjson = json_encode($images_url);

        $data = [
            'message'      => $r->message,
            'status'       => 1,
            'images'       => $imagesjson,
            'channel_id'   => $channelId
        ];

        $message = auth()->user()->messages()->create($data);
        if ($message) {
            if ($r->sendNotification) {
                $user = User::where('id', $r->userID)->with('mobileSubscriptions')->first();
                if ($user) {
                    $notification = $user->notify(new MessageInbox(auth()->user(), $order, $message, $chatRoom, $user->mobileSubscriptions));
                }
            }
            broadcast(new MessageSend($order, new MessageResources($message), auth()->id(), $r->userID, $chatRoom))->toOthers();
        }
        return new MessageResources($message);
    }

    public function received_message($id, Request $r)
    {

        $message = Message::where('id', $id)->first();

        if (auth()->id() != $message->sender_id and !$r->seen) {
            return response()->json(['message'=> 'Not allow', 'samecurrent'=> false],400);
        }

        if (!$message) {
            return response()->json(['message'=> 'no result found.'],400);
        }

        event(new MessageRecived($message, $r->status, $r->seen, $r->notificationId));
        return response()->json(['message'=> 'Recived'],200);
    }

}
