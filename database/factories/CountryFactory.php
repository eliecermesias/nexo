<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('??')),
            'name' => fake()->unique()->country(),
            'is_active' => true,
        ];
    }

    public function colombia(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'CO',
            'name' => 'Colombia',
            'is_active' => true,
        ]);
    }
}
