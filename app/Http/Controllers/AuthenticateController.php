<?php

namespace App\Http\Controllers;

use App\User;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{

	public function __construct(){
		$this->middleware('jwt.auth')->only(['test', 'logout']);
	}

    public function register(Request $request)
    {
    	$this->validate($request, [
    		"email" => "required|email|unique:users",
    		"name" => "required|string",
    		"password" => "required|confirmed",
    	]);
    	$user = new User([
    		'name' => $request->input('name'),
    		'email' => $request->input('email'),
    		'password' => bcrypt($request->input('password')),
    	]);
    	$test = $user->save();
    	return response()->json(['message' => 'User created'], 201);
    }

    public function login(Request $request)
    {
    	$credentials = $request->only('email', 'password');
    	try {
    		if (! $token = JWTAuth::attempt($credentials)) {
    			return response()->json(['error' => 'invalid_credentials'], 401);
    		} 
    	} catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = JWTAuth::toUser($token);
        return response()->json(compact('token', 'user'));
    }

    public function test()
    {
	    if (! $user = JWTAuth::parseToken()->authenticate()) {
	        return response()->json(['user_not_found'], 404);
	    }
    	return response()->json(compact('user'));
    }

    public function logout(Request $request)
    {
		JWTAuth::invalidate();
		return response()->json(['message' => 'Logged out successfully']);    	
    }
}
