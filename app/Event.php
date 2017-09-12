<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $dates = ['begin_at', 'end_at'];
	protected $fillable = [
		"name",
		"description",
		"address",
		"begin_at",
		"end_at",
		"user_id"
	];

    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
