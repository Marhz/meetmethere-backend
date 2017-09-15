<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventsParticipationTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function a_user_can_participate_to_an_event()
	{
		$this->setClientToken();
		$event = create('App\Event');
		$this->assertCount(0, $event->participants);
		$this->postJson(route('participate.store', ['token' => $this->userToken, 'event' => $event->id]));
		$this->assertCount(1, $event->fresh()->participants);
	}

	/**
	 * @test
	 */
	function a_user_cannot_participate_to_the_same_event_twice()
	{
		$this->setClientToken();
		$event = create('App\Event');
		$this->postJson(route('participate.store', ['token' => $this->userToken, 'event' => $event->id]));
		$this
			->postJson(route('participate.store', ['token' => $this->userToken, 'event' => $event->id]))
			->assertStatus(403);
		$this->assertCount(1, $event->fresh()->participants);
	}

	/**
	 * @test
	 */
	function a_user_can_cancel_his_participation()
	{
		$this->setClientToken();
		$event = create('App\Event');
		// $this->postJson(route('participate.store', ['token' => $this->userToken, 'event' => $event->id]));
		// $this->assertCount(1, $event->fresh()->participants);
		$response = $this->json('delete', route('participate.destroy', ['token' => $this->userToken, 'event' => $event->id]));
		$this->assertCount(0, $event->fresh()->participants);
	}
}
