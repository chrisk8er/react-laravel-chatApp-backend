<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Channel;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = [
        //     'message'      => "Hi",
        //     'status'       => 1,
        //     'images'       => "{}",
        //     'channel_id'   => 2
        // ];

        // $message = auth()->user()->messages()->create($data);
        // Channel::create(['sender_id' => 3, 'receiver_id' => 1]);

        return view('home');
    }
}
