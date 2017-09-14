<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventMapController extends Controller
{
    public function index(Request $request)
    {
    	$ids = collect(Event::getByDistance(
    			$request->input('lat'),
    			$request->input('lng'),
    			$request->input('distance')
    		))->pluck('id');
    	$events = Event::whereIn('id', $ids)->get();
    	return response()->json(['data' => $events]);
    }
}
