<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->user = create('App\User');
	}

	/**
	 * @test
	 */
	function it_has_many_events()
	{
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->user->events);
	}
}
