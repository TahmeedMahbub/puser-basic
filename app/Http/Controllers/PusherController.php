<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function broadcast(Request $request)
    {
        broadcast(new PusherBroadcast($request->get(key: 'message')))->toOthers();
        return view('broadcast', ['message' => $request->get(key: 'message')]);
    }

    public function receive(Request $request)
    {
        //
        return view('receive', ['message' => $request->get(key: 'message')]);
    }
}
