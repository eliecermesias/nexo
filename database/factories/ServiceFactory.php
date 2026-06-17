<?php

namespace Database\Factories;

use App\Models\Currency;
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
        $currency = Currency::query()->firstOrCreate(
            ['code' => 'COP'],
            ['name' => 'Peso colombiano', 'symbol' => '$', 'decimal_place' => 2],
        );

        return [
            'team_id' => $owner->currentTeam?->id,
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'code' => strtoupper(fake()->unique()->bothify('SRV-###')),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['Consultoría', 'Capacitación', 'Auditoría']),
            'pricing_type' => fake()->randomElement(['fixed', 'hourly', 'monthly', 'custom']),
            'unit' => fake()->randomElement(['servicio', 'hora', 'mes']),
            'unit_price' => fake()->numberBetween(100000, 1500000),
            'currency_id' => $currency->getKey(),
            'is_active' => true,
        ];
    }
}
