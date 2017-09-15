<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventsMapTest extends TestCase
{
	use DatabaseMigrations;
	use DatabaseTransactions;

	/**
	 * @test
	 */
	function it_get_all_the_events_within_a_given_distance()
	{
		// $eventIn = create('App\Event', ['latitude' => '48', 'longitude' => '2']);
		// $eventNotIn = create('App\Event', ['latitude' => '2', 'longitude' => '48']);
		// $response = $this->json('get', route('events.map.index',
		// 	['lat' => "48.002", 'lng' => '2.002', 'distance' => '150'])
		// );
		// $this->assertCount(1, $response->json());
		// $this->assertTrue(true);
	}
}
