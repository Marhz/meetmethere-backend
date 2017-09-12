<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->event = create('App\Event');
	}

	/**
	 * @test
	 */
	function it_belongs_to_a_user()
	{
		$this->assertInstanceOf('App\User', $this->event->creator);
	}

	/**
	 * @test
	 */
	function dates_are_carbon_instance()
	{
		$this->assertInstanceOf('Carbon\Carbon', $this->event->begin_at);
		$this->assertInstanceOf('Carbon\Carbon', $this->event->end_at);
	}
}
