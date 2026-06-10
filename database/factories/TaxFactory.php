<?php

namespace Database\Factories;

use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tax>
 */
class TaxFactory extends Factory
{
    protected $model = Tax::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtolower(fake()->unique()->bothify('tax_##')),
            'name' => 'IVA '.fake()->randomElement([0, 5, 19]).'%',
            'rate' => fake()->randomElement([0, 5, 19]),
            'is_active' => true,
        ];
    }
}
