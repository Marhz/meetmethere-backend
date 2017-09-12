<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
    	return $this->belongsTo(User::class);
    }
}
