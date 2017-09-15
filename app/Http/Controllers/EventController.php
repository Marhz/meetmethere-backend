<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use JWTAuth;

class EventController extends Controller
{

    public function __construct(){
        $this->middleware(['jwt.auth'])->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $data = $request->only([
            "name",
            "description",
            "address",
            "begin_at",
            "end_at",
            "latitude",
            "longitude"
        ]);
        $data['user_id'] = $user->id;
        $event = Event::create($data);
        $event->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return response()->json(['data' => $event->load('participants')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $user = JWTAuth::parseToken()->toUser();
        if($user->id == $event->user_id) {
            return response()->json(['data' => $event]);
        }
        return response()->json(['message' => "Unauthorized"], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $user = JWTAuth::parseToken()->toUser();
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        if($user->id != $event->user_id) {
            return response()->json(['message' => 'access forbidden'], 403);
        }
        $event->update($request->only([
            "name",
            "description",
            "address",
            "begin_at",
            "end_at",
            "latitude",
            "longitude"
        ]));
        return response()->json(['message' => 'Event updated', 'event' => $event], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $user = JWTAuth::parseToken()->toUser();
        if($user->id != $event->user_id ) {
            return response()->json(['error' => 'access forbidden'], 403);
        }
        $event->delete();
        return response()->json(['message' => 'Event deleted'], 200);
    }
}
