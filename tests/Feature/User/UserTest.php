<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @group feature
     * @group api
     */
    public function testGetAllUsersWithLimitAndPage(): void
    {
        User::factory()->count(2)->create();
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('get', route('user.index', ['page' => 1, 'limit' => 3]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->etc()
            )
            ->assertJsonCount(2);
    }

    //        //TODO get user by post

    /**
     * @group feature
     * @group api
     */
    public function testCreateUser(): void
    {
        $adminUser = User::factory()->adminRole()->create();
        $pw = bcrypt('soSecret');

        $this->actingAs($adminUser)
            ->json('post', route('user.store', [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'johnRandom@gmail.com',
                'password' => $pw,
                'password_confirmation' => $pw,
                'mobile_number' => '0834567890',
            ]))
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'first_name' => 'John',
            'email' => 'johnRandom@gmail.com'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateUserByIdentifier(): void
    {
        $user = User::factory()->basicUserRole()->create();
        $adminUser = User::factory()->adminRole()->create();

        $this->actingAs($adminUser)
            ->json('patch', route('user.update', [
                'user' => $user->id,
                'first_name' => 'updatedName'
            ]))
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'first_name' => 'updatedName'
        ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetUserByIdentifier(): void
    {
        $user = User::factory()->basicUserRole()->create();
        $adminUser = User::factory()->adminRole()->create();

        $this->actingAs($adminUser)
            ->json('get', route('user.show', ['user' => $user->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'first_name' => $user->first_name,
                'email'      => $user->email
            ]);
    }

    /**
     * @group feature
     * @group api
     */
    public function testGetUserByIncorrectIdentifier(): void
    {
        $user = User::factory()->basicUserRole()->create();
        $adminUser = User::factory()->adminRole()->create();

        $this->actingAs($adminUser)
            ->json('get', route('user.show', ['user' => $user->id + (int) $adminUser->id]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group feature
     * @group api
     */
    public function testUpdateUserWithExistingEmail(): void
    {
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('patch', route('user.update', [
                'user' => $user->id,
                'email' => $user->email
            ]))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @group feature
     * @group api
     */
    public function testDeleteUserByIdentifier(): void
    {
        $user = User::factory()->adminRole()->create();

        $this->actingAs($user)
            ->json('delete', route('user.destroy', ['user' => $user->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

}
