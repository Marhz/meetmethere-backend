<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class commentsTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_delete_comments()
	{
		$this->setClientToken();
		$comment = create('App\Comment', ['user_id' => $this->user->id]);
		$response = $this->json('delete', route('comments.destroy', [$comment->id, 'token' => $this->userToken]))
			->assertStatus(200);
	}

	/**
	 * @test
	 */
	function a_comment_can_be_edited()
	{
		$this->setClientToken();
		$comment = create('App\Comment', ['user_id' => $this->user->id]);
		$response = $this->json('put', route('comments.update', [
				'token' => $this->userToken,
				'comment_id' => $comment->id,
				'content' => "edited comment"
			]))
			->assertStatus(200);
		$this->assertEquals($comment->fresh()->content, "edited comment");
	}
}
