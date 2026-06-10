<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

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
            'code' => strtoupper(fake()->unique()->bothify('SRV-###')),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'unit' => fake()->randomElement(['servicio', 'hora', 'mes']),
            'unit_price' => fake()->numberBetween(100000, 1500000),
            'is_active' => true,
        ];
    }
}
