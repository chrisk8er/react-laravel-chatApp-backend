<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Channel;
use App\User;

class Message extends Model
{
    protected $fillable = [
        'sender_id', 'message', 'status','images', 'channel_id'
    ];

    public function channel()
    {
       return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function sender()
    {
       return $this->belongsTo(User::class, 'sender_id');
    }
}
