<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @group feature
     * @group api
     */
    public function testGetAllCategoriesWithLimitAndPage(): void
    {
        Category::factory()->count(2)->create();
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('get', route('category.index', ['page' => 1, 'limit' => 3]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->etc()
            )
            ->assertJsonCount(2);
    }

//    //        //TODO get user by post

    /**
     * @group feature
     * @group api
     */
    public function testCreateCategory(): void
    {
        $adminUser = User::factory()->adminRole()->create();

        $this->actingAs($adminUser)
            ->json('post', route('category.store', []),
                [
                    'user_id'       => $adminUser->id,
                    'name'          => 'test name',
                    'description'   => 'test description'
                ]
            )
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('categories', [
            'name'          => 'test name',
            'description'   => 'test description'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateCategoryByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $category = Category::factory()->create();

        $this->actingAs($adminUser)
            ->json('patch', route('category.update', [
                'category'   => $category->id,
            ]),
                [
                    'name'          => 'updated name',
                    'description'   => 'updated description'
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('categories', [
            'name'          => 'updated name',
            'description'   => 'updated description'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetCategoryByIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $category = Category::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('category.show', ['category' => $category->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name'          => $category->name,
                'description'   => $category->description
            ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetCategoryByIncorrectIdentifier(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $category = Category::factory()->create();

        $this->actingAs($adminUser)
            ->json('get', route('category.show', ['category' => $category->id + 1]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateCategory(): void
    {
        $user = User::factory()->adminRole()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->json('patch', route('category.update', [
                'category'  => $category->id,
            ]), [
                    'name'          => 'modified name',
                    'description'   => 'example description'
                ]
            )
            ->assertStatus(Response::HTTP_OK);

        self::assertDatabaseHas('categories', [
            'id'            => $category->id,
            'name'          => 'modified name',
            'description'   => 'example description'
        ]);
    }
}
