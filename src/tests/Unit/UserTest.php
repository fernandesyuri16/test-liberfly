<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService();
    }

    public function test_creates_user_successfully(): void
    {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'description' => fake()->sentence(),
            'password' => fake()->password()
        ];

        $response = $this->userService->createUser($userData);

        $this->assertEquals(Response::HTTP_CREATED, $response['code']);
        $this->assertArrayHasKey('id', $response['response']['data']);
    }

    public function test_gets_user_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->userService->getUser($user->id);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertArrayHasKey('id', $response['response']['data']);
    }

    public function test_updates_user_successfully(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $newName = 'Yuri Fernandes';

        $response = $this->userService->updateUser($user->id, ['name' => $newName]);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals($newName, $response['response']['data']['name']);
    }

    public function test_deletes_user_successfully(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->userService->deleteUser($user->id);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals('User successfully deleted!', $response['response']['data']);
    }

    public function test_error_if_user_not_found_when_getting_user(): void
    {
        $response = $this->userService->getUser(999);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("User doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_user_not_found_when_updating_user(): void
    {
        $response = $this->userService->updateUser(999, ['name' => 'Yuri Fernandes']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("User doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_user_not_found_when_deleting_user(): void
    {
        $response = $this->userService->deleteUser(999);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("User doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_user_doesnt_have_permission_when_updating_user(): void
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->userService->updateUser($secondUser->id, ['name' => 'Yuri Fernandes']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response['code']);
        $this->assertEquals("You don't have permission to update or delete this user.", $response['response']['data']);
    }

    public function test_error_if_user_doesnt_have_permission_when_deleting_user(): void
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->userService->deleteUser($secondUser->id);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response['code']);
        $this->assertEquals("You don't have permission to update or delete this user.", $response['response']['data']);
    }
}
