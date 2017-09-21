<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use JWTAuth;

class EventController extends Controller
{

    public function __construct(){
        $this->middleware(['jwt.auth'])->only(['store', 'edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::paginate(Event::PER_PAGE);
        return response()->json(['data' => $events]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['_banner' => 'image']);
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
        if ($request->hasFile('_banner')) {
            $path = $request->banner->store('public/banners');
            $data['banner'] = $path;
        }
        $data['user_id'] = $user->id;
        $event = Event::create($data);
        $event->participants()->attach($user->id);
        return response()->json(['message' => 'Event created successfully', 'id' => $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return response()->json(['data' => $event->load('participants', 'creator')]);
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
        $data = $request->only([
            "name",
            "description",
            "address",
            "begin_at",
            "end_at",
            "latitude",
            "longitude"
        ]);
        if ($request->hasFile('banner')) {
            $path = $request->banner->store('public/banners');
            $data['banner'] = $path;
        }
        $event->update($data);
        return response()->json(['message' => 'Event updated', 'id' => $event->id], 200);
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
