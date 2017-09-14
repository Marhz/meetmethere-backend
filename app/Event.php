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
		"user_id",
		"latitude",
		"longitude"
	];

    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public static function getByDistance($lat,$lng,$distance)
    {
      // This will calculate the distance in km
      // if you want in miles use 3959 instead of 6371
    	$ids = collect(\DB::select(
    		\DB::raw(
    			'SELECT id, ( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(latitude) ) ) ) AS distance FROM events HAVING distance < ' . $distance . ' ORDER BY distance'
    		)

    	))->pluck('id');
    	return static::whereIn('id', $ids)->get();
    }
}
