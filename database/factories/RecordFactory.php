<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $operation_id = fake()->numberBetween(1, 6);
        $result = $operation_id == 6 ? fake()->text(10) : fake()->numberBetween(1, 99);

        return [
            'user_id' => 1,
            'amount' => 1,
            'operation_id' => $operation_id,
            'operation_response' => $result,
            'created_at' => fake()->dateTime,
            'updated_at' => fake()->dateTime,
            'deleted_at' => fake()->numberBetween(1, 10) == 10 ? now() : null
        ];
    }
}
