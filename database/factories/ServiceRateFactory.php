<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Service;
use App\Models\ServiceRate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceRate>
 */
class ServiceRateFactory extends Factory
{
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
            'service_id' => Service::factory(),
            'currency_id' => $currency->getKey(),
            'pricing_type' => fake()->randomElement(['fixed', 'hourly', 'monthly', 'custom']),
            'base_price' => fake()->randomFloat(2, 100000, 2500000),
            'starts_on' => now()->toDateString(),
            'ends_on' => null,
            'is_active' => true,
        ];
    }
}
