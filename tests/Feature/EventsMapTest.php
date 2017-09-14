<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventsMapTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_get_all_the_events_within_a_given_distance()
	{
		$eventIn = create('App\Event', ['latitude' => '48', 'longitude' => '2']);
		$eventNotIn = create('App\Event', ['latitude' => '2', 'longitude' => '48']);
		$response = $this->json('get', route('events.map.index',
			['latitude' => "40.02", 'longitude' => '2.02', 'distance' => '5'])
		);

	}
}
