<?php

namespace Tests\Feature;

use JWTAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_creates_a_user()
	{
		$user = make('App\User');
		$data = $user->toArray();
		$data['password'] = 1234;
		$data['password_confirmation'] = 1234;
		$this->json('post', route('auth.register'), $data)
			->assertStatus(201);
		$this->assertDatabaseHas('users', ['email' => $user->email]);
	}

	/**
	 * @test
	 */
	function it_generates_a_token_when_credentials_are_valid()
	{
		$user = create('App\User', ['password' => bcrypt(1234)]);
		$data = ['email' => $user->email, 'password' => 1234];
		$response = $this->json('post', route('auth.login'), $data)
			->assertStatus(200);
	}

	/**
	 * @test
	 */
	function it_refuses_invalid_credentials()
	{
		$user = create('App\User', ['password' => bcrypt(1234)]);
		$data = ['email' => $user->email, 'password' => 123];
		$response = $this->json('post', route('auth.login'), $data)
			->assertStatus(401);
	}

	/**
	 * @test
	 */
	function it_recognize_the_token()
	{
		$this->setClientToken();
		$response = $this->json('get', "http://meetmethere.dev/api/test?token={$this->userToken}")
			->assertStatus(200);
	}
}