<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use  App\User;

class Channel extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id'
    ];

    public function sender()
    {
       return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
       return $this->belongsTo(User::class, 'receiver_id');
    }
}
