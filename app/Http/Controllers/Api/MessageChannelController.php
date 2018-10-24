<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Message;
use App\Model\Channel;

use App\Http\Resources\MessageCollection;
use App\Events\MessageSend;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\Message as MessageResources;


class MessageChannelController extends Controller
{
    public function chatList()
    {

        $channels = Channel::where('sender_id', auth()->id())->orWhere('receiver_id',auth()->id())->get();
        $array_channelsIds = [];
        foreach ($channels as $key => $channel) {
            $array_channelsIds[] = $channel->id;
        }

        $messages = Message::whereIn('channel_id', $array_channelsIds)
                            ->with(['channel.sender','channel.receiver', 'sender'])
                            ->latest()->get();

        $newMessages = collect(new MessageCollection($messages));

        $newMesssssages = $newMessages->groupBy('channelID');

        $allmessages = [];

        foreach ($newMesssssages as $key => $allmessage) {

            $allmessages[] = $allmessage[0];
        }

        return response()->json(['data'=> $allmessages],200);

    }

    public function messages($channelId)
    {

        $messages = Message::where('channel_id', $channelId)
                            ->with(['channel.sender','channel.receiver', 'sender'])
                            ->get();

        return new MessageCollection($messages);
    }

    public function send_message($channelID, Request $r)
    {
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
            'channel_id'   => $channelID
        ];

        $message = auth()->user()->messages()->create($data);

        if ($message) {
            broadcast(new MessageSend(new MessageResources($message), $channelID))->toOthers();
        }

        return new MessageResources($message);
    }

    private function storeImage($images)
    {
        $images_url = [];
        foreach ($images as $key =>$file_data) {
            $file_name = 'messages/image/image_'.time().'-'.$key.'.png';
            @list($type, $file_data) = explode(';', $file_data);
            @list(, $file_data)  = explode(',', $file_data);
            if($file_data!=""){
                $path = Storage::put($file_name,base64_decode($file_data));
            }else {
                $path = null;
            }
            array_push($images_url, $file_name);
        }

        return  $images_url;
    }

}
