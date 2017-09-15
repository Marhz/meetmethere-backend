<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ParticipationController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

    public function store(Event $event)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	if($event->participants->contains($user->id)) {
    		return response()->json(['message', 'You already participate to this event'], 403);
    	}
    	$event->participants()->attach($user->id);
    }

    public function destroy(Event $event)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$event->participants()->detach($user->id);
    }
}
