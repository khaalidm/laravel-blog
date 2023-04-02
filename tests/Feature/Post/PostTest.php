<?php

namespace Tests\Feature\Post;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @group feature
     * @group api
     */
    public function testGetAllPostsWithLimitAndPage(): void
    {
        Post::factory()->count(2)->create();
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('get', route('post.index', ['page' => 1, 'limit' => 3]))
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
    public function testCreatePost(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $category  = Category::factory()->create();

        $this->actingAs($adminUser)
            ->json('post', route('post.store', []),
                [
                    'category_id'   => $category->id,
                    'user_id'       => $adminUser->id,
                    'title'         => 'test title',
                    'description'   => 'test description',
                    'body'          => '<body> this is the body </body>'
                ]
            )
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('posts', [
            'category_id'   => $category->id,
            'user_id'       => $adminUser->id,
            'title'         => 'test title',
            'description'   => 'test description',
            'body'          => '<body> this is the body </body>'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdatePostByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $post      = Post::factory()->create();

        $this->actingAs($adminUser)
            ->json('patch', route('post.update', [
                'post'   => $post->id,
            ]),
                [
                    'title'          => 'updated title',
                    'description'   => 'updated description'
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('posts', [
            'title'         => 'updated title',
            'description'   => 'updated description'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdatePostByIdentifierWithInvalidUserRole(): void
    {
        $basicUser = User::factory()->basicUserRole()->create();
        $post      = Post::factory()->create();

        $this->actingAs($basicUser)
            ->json('patch', route('post.update', [
                'post'   => $post->id,
            ]),
                [
                    'title'          => 'updated title',
                    'description'   => 'updated description'
                ]
            )
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetPostByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $post = Post::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('post.show', ['post' => $post->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'title'          => $post->title,
                'description'    => $post->description
            ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetPostByIncorrectIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $post      = Post::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('post.show', ['post' => $post->id + 1]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdatePost(): void
    {
        $user = User::factory()->adminRole()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->json('patch', route('post.update', [
                'post'  => $post->id,
            ]), [
                    'title'         => 'modified title',
                    'description'   => 'example description',
                    'body'          => '<body> this is the body </body>'
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        self::assertDatabaseHas('posts', [
            'id'            => $post->id,
            'title'         => 'modified title',
            'description'   => 'example description',
            'body'          => '<body> this is the body </body>'
        ]);
    }

    public function testListPostsByUser(): void
    {
        $user = User::factory()->adminRole()->create();
        Post::factory()->count(5)->create([
           'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('post', route('post.list_by_user', [
                'user_id'  => $user->id,
            ]))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testListPostsByCategory(): void
    {
        $user = User::factory()->adminRole()->create();
        $category = Category::factory()->create();
        Post::factory()->count(5)->create([
            'category_id' => $category->id
        ]);

        $this->actingAs($user)
            ->json('post', route('post.list_by_category', [
                'category_id'  => $category->id,
            ]))
            ->assertStatus(Response::HTTP_OK);
    }

    public function testListPostsByCategoryWithIncorrectPrivileges(): void
    {
        $userBasic = User::factory()->basicUserRole()->create();
        $category = Category::factory()->create();
        Post::factory()->count(5)->create([
            'category_id' => $category->id
        ]);

        $this->actingAs($userBasic)
            ->json('post', route('post.list_by_category', [
                'category_id'  => $category->id,
            ]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
