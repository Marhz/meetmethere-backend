<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventsApiTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_gets_all_events()
	{
		$events = create('App\Event', [], Event::PER_PAGE * 3);
		$response = $this->getJson(route('events.index'))
			->assertStatus(200);
		$responseArray = $response->getData()->data->data;
		foreach ($responseArray as $key => $val) {
			$responseArray[$key] = (array) $val;
		}
		$this->assertEquals($responseArray, array_slice($events->toArray(), 0, Event::PER_PAGE));
		$response = $this->get(route('events.index', ['page' => 2]))
			->assertStatus(200);
		$responseArray = $response->getData()->data->data;
		foreach ($responseArray as $key => $val) {
			$responseArray[$key] = (array) $val;
		}
		$this->assertEquals($responseArray, array_slice($events->toArray(), Event::PER_PAGE , Event::PER_PAGE));
	}

	/**
	 * @test
	 */
	function it_gets_one_event()
	{
		$event = create('App\Event');
		$response = $this->json('get', route('events.show', $event->id))
			->assertStatus(200)
			->assertJson(['data' => $event->toArray()]);
	}

	/**
	 * @test
	 */
	function a_user_can_submit_an_event_when_logged_in()
	{
		$this->setClientToken();
		$event = make('App\Event');
		$response = $this
			->json('post', route('events.store', ['token' => $this->userToken]), $event->toArray())
			->assertStatus(200);
		$this->assertDatabaseHas('events', ['name' => $event->name]);
		$this->assertEquals(\App\Event::first()->user_id, $this->user->id);
	}

	/**
	 * @test
	 */
	function a_logged_in_user_can_update_his_event()
	{
		$this->setClientToken();
		$event = create('App\Event', ['user_id' => $this->user->id]);
		$event->name = "new name";
		$this->json('put', route('events.update', [$event->id, 'token' => $this->userToken]), $event->toArray())
			->assertStatus(200);
		$this->assertEquals($event->fresh()->name, "new name");
	}

	/**
	 * @test
	 */
	function a_user_cannot_update_another_user_event()
	{
		$this->setClientToken();
		create('App\Event', ['user_id' => $this->user->id]);
		$event = create('App\Event');
		$event->name = "new name";
		$this->json('put', route('events.update', [$event->id, 'token' => $this->userToken]), $event->toArray())
			->assertStatus(403);
		$this->assertNotEquals($event->fresh()->name, "new name");
	}

	/**
	 * @test
	 */
	function a_user_can_delete_his_event()
	{
		$this->setClientToken();
		$event = create('App\Event', ['user_id' => $this->user->id]);
		$this->json('delete', route('events.destroy', [$event->id, 'token' => $this->userToken]))
			->assertStatus(200);
		$this->assertDatabaseMissing('events', ['id' => $event->id]);
	}

	/**
	 * @test
	 */
	function a_user_cannot_delete_another_user_event()
	{
		$this->setClientToken();
		$event = create('App\Event');
		$this->json('delete', route('events.destroy', [$event->id, 'token' => $this->userToken]))
			->assertStatus(403);
		$this->assertDatabaseHas('events', ['id' => $event->id]);
	}
}
