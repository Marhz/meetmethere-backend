<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'AuthenticateController@register')->name('auth.register');
Route::post('/login', 'AuthenticateController@login')->name('auth.login');
Route::get('/events/map', 'EventMapController@index')->name('events.map.index');

Route::resource('/events', 'EventController');

route::get('/logout', 'AuthenticateController@logout');

Route::get('/testhero', function (Request $request) {
	return response()->json(
		['id' =>1,"name" =>'test']
	);
});

Route::get('events/{id}/comments', "CommentController@getCommentsFromId");
Route::post('events/{event}/comments', "CommentController@store");
Route::put('comments/edit', "CommentController@update")->name('comments.update');
Route::delete('comments/{comment}/delete', 'CommentController@destroy')->name('comments.destroy');

Route::post('events/{event}/participate', 'ParticipationController@store')->name('participate.store');
Route::delete('events/{event}/participate/cancel', 'ParticipationController@destroy')->name('participate.destroy');
// Route::get('/events', 'EventController@index')->name('event.index');

Route::get('refreshToken', 'AuthenticateController@refresh')->name('token.refresh');
