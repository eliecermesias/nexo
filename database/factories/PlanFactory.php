<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = User::query()->first() ?? User::factory()->create();

        return [
            'team_id' => $owner->currentTeam?->id,
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'code' => fake()->unique()->bothify('PLAN-####'),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'billing_period' => fake()->randomElement(['one_time', 'monthly', 'quarterly', 'semiannual', 'annual']),
            'price' => fake()->randomFloat(2, 100000, 5000000),
            'is_active' => true,
        ];
    }
}
