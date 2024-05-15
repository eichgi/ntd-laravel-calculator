<?php

namespace Tests\Feature;

use App\Http\enums\operations;
use App\Models\User;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_calculate_addition_and_successful_response(): void
    {
        $user = User::factory()->create();

        $firstValue = fake()->numberBetween(1, 10);
        $secondValue = fake()->numberBetween(1, 10);

        $response = $this
            ->actingAs($user)
            ->post('v1/operation', [
                'operationType' => operations::ADDITION->name,
                'firstValue' => $firstValue,
                'secondValue' => $secondValue
            ]);

        $response->assertOk()->assertJson(['result' => $firstValue + $secondValue]);
    }

    public function test_insufficient_credit_message_when_users_balance_ran_out()
    {
        $user = User::factory()->create(['balance' => 0]);

        $response = $this
            ->actingAs($user)
            ->post('/v1/operation', [
                'operationType' => operations::RANDOM_STRING->name
            ]);

        $response->assertUnprocessable()->assertJson(['message' => 'Insufficient credit.']);
    }

    public function test_create_and_display_three_ops()
    {
        $user = User::factory()->create();
        $zeroFallback = 0;

        $response = $this
            ->actingAs($user)
            ->post('/v1/operation', [
                'operationType' => operations::SQUARE_ROOT->name,
                'firstValue' => 25
            ]);
        $response->assertOk();

        $response = $this
            ->actingAs($user)
            ->post('/v1/operation', [
                'operationType' => operations::MULTIPLICATION->name,
                'firstValue' => 6,
                'secondValue' => 8
            ]);
        $response->assertOk();

        $response = $this
            ->actingAs($user)
            ->post('/v1/operation', [
                'operationType' => operations::DIVISION->name,
                'firstValue' => 2,
                'secondValue' => 0
            ]);

        $response->assertOk()->assertJson(['result' => $zeroFallback]);

        $response = $this
            ->actingAs($user)
            ->get('/v1/operation?page=1');

        $this->assertArrayHasKey('current_page', $response->original);
        $this->assertArrayHasKey('next_page_url', $response->original);
        $this->assertArrayHasKey('prev_page_url', $response->original);
        $this->assertArrayHasKey('total', $response->original);
        $this->assertEquals(3, count($response->original['records']));
    }
}
