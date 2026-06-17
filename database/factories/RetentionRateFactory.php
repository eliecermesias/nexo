<?php

namespace Database\Factories;

use App\Models\RetentionRate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RetentionRate>
 */
class RetentionRateFactory extends Factory
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
            'code' => fake()->unique()->bothify('RET-####'),
            'name' => fake()->unique()->words(2, true),
            'rate' => fake()->randomFloat(4, 0, 15),
            'is_active' => true,
        ];
    }
}
