<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Event;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function __construct(){
		$this->middleware('jwt.auth')->only(['store', 'destroy', 'update']);
	}

    public function getCommentsFromId($eventId)
    {
    	$comments =  Comment::with('author')->where('event_id', $eventId)->get();
    	return response()->json(['data' => $comments]);
    }

    public function store(Request $request, Event $event)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	$comment = Comment::create([
    		'content' => $request->input('content'),
    		'user_id' => $user->id,
    		'event_id' => $event->id
    	]);
    	$comment->author = $user;
    	return response()->json(['message' => 'Comment stored!', 'data' => $comment]);
    }

    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $comment = Comment::find($request->input('comment_id'));
        if($user->id != $comment->user_id) {
            return response()->json(['message' => $comment], 403);
        }
        $comment->update(['content' => $request->input('content')]);
        return response()->json(['message' => 'Comment edited!']);
    }

    public function destroy(Request $request, Comment $comment)
    {
    	$user = JWTAuth::parseToken()->toUser();
    	if($user->id != $comment->user_id) {
    		return response()->json(['message' => $comment], 403);
    	}
    	$comment->delete();
    	return response()->json(['message' => 'Comment deleted!']);
    }
}
