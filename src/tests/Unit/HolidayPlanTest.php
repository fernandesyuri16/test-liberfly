<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\HolidayPlan;
use App\Services\HolidayPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class HolidayPlanTest extends TestCase
{
    use RefreshDatabase;

    protected HolidayPlanService $holidayPlanService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->holidayPlanService = new HolidayPlanService();
    }

    public function test_creates_holiday_plan_successfully(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $holidayData = [
            'user_id' => $user->id,
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'date' => fake()->dateTimeBetween('now', '+1 year'),
            'location' => fake()->city,
            'participants' => fake()->randomNumber(2),
        ];

        $response = $this->holidayPlanService->createHolidayPlan($holidayData);

        $this->assertEquals(Response::HTTP_CREATED, $response['code']);
        $this->assertArrayHasKey('id', $response['response']['data']);
    }

    public function test_gets_holiday_plan_successfully(): void
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $response = $this->holidayPlanService->getHolidayPlan($holidayPlan->id);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertArrayHasKey('id', $response['response']['data']);
    }

    public function test_updates_holiday_plan_successfully(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $holidayData = [
            'user_id' => $user->id,
        ];

        $holidayPlan = HolidayPlan::factory()->create($holidayData);

        $newTitle = 'New Travel';

        $response = $this->holidayPlanService->updateHolidayPlan($holidayPlan->id, ['title' => $newTitle]);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals($newTitle, $response['response']['data']['title']);
    }

    public function test_deletes_holiday_plan_successfully(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $holidayData = [
            'user_id' => $user->id,
        ];

        $holidayPlan = HolidayPlan::factory()->create($holidayData);

        $response = $this->holidayPlanService->deleteHolidayPlan($holidayPlan->id);

        $this->assertEquals(Response::HTTP_OK, $response['code']);
        $this->assertEquals('Holiday plan successfully deleted!', $response['response']['data']);
    }

    public function test_error_if_holiday_plan_not_found_when_getting_holiday_plan(): void
    {
        $response = $this->holidayPlanService->getHolidayPlan(123);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("Holiday plan doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_holiday_plan_not_found_when_updating_holiday_plan(): void
    {
        $response = $this->holidayPlanService->updateHolidayPlan(123, ['name' => 'New Travel']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("Holiday plan doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_holiday_plan_not_found_when_deleting_holiday_plan(): void
    {
        $response = $this->holidayPlanService->deleteHolidayPlan(123);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response['code']);
        $this->assertEquals("Holiday plan doesn't exists.", $response['response']['data']);
    }

    public function test_error_if_user_doesnt_have_permission_when_updating_holiday_plan(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $holidayData = [
            'user_id' => $user->id,
        ];

        $holidayPlan = HolidayPlan::factory()->create($holidayData);

        $secondUser = User::factory()->create();
        Sanctum::actingAs($secondUser);

        $response = $this->holidayPlanService->updateHolidayPlan($holidayPlan->id, ['name' => 'New Travel']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response['code']);
        $this->assertEquals("You don't have permission to perform this action.", $response['response']['data']);
    }

    public function test_error_if_user_doesnt_have_permission_when_deleting_holiday_plan(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $holidayData = [
            'user_id' => $user->id,
        ];

        $holidayPlan = HolidayPlan::factory()->create($holidayData);

        $secondUser = User::factory()->create();
        Sanctum::actingAs($secondUser);

        $response = $this->holidayPlanService->deleteHolidayPlan($holidayPlan->id);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response['code']);
        $this->assertEquals("You don't have permission to perform this action.", $response['response']['data']);
    }
}