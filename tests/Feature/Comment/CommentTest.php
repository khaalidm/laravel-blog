<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @group feature
     * @group api
     */
    public function testGetAllCommentsWithLimitAndPage(): void
    {
        Comment::factory()->count(2)->create();
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('get', route('comment.index', ['page' => 1, 'limit' => 3]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->etc()
            )
            ->assertJsonCount(2);
    }

    /**
     * @group feature
     * @group api
     */
    public function testCreateCategory(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $post      = Post::factory()->create();

        $this->actingAs($adminUser)
            ->json('post', route('comment.store', []),
                [
                    'user_id'       => $adminUser->id,
                    'post_id'       => $post->id,
                    'text'          => 'test text'
                ]
            )
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('comments', [
            'user_id'       => $adminUser->id,
            'post_id'       => $post->id,
            'text'          => 'test text'
        ]);
    }

    public function testCreateCommentExceedingTextLimit(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $post      = Post::factory()->create();

        $this->actingAs($adminUser)
            ->json('post', route('comment.store', []),
                [
                    'user_id'       => $adminUser->id,
                    'post_id'       => $post->id,
                    'text'          => fake()->realTextBetween(401,500)
                ]
            )
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateCategoryByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $comment   = Comment::factory()->create();

        $this->actingAs($adminUser)
            ->json('patch', route('comment.update', [
                'comment'   => $comment->id,
            ]),
                [
                    'text'          => 'updated text'
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('comments', [
            'text'          => 'updated text'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetCommentByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('comment.show', ['comment' => $comment->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'text' => $comment->text
            ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetCommentByIncorrectIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $comment   = Comment::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('comment.show', ['comment' => $comment->id + 1]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateComment(): void
    {
        $user = User::factory()->adminRole()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'text'    => 'test comment'
        ]);

        $this->actingAs($user)
            ->json('patch', route('comment.update', [
                'comment' => $comment->id,
            ]), [
                    'text' => 'modified comment',
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        self::assertDatabaseHas('comments', [
            'id'    => $comment->id,
            'text'  => 'modified comment'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateCommentByDifferentUserFails(): void
    {
        $user = User::factory()->adminRole()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'text'    => 'test comment'
        ]);

        $this->actingAs($otherUser)
            ->json('patch', route('comment.update', [
                'comment' => $comment->id,
            ]), [
                    'text' => 'modified comment',
                ]
            )
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
