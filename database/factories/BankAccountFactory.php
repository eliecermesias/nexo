<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
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
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'enterprises_Id' => Enterprise::factory(),
            'banks_Id' => Bank::factory(),
            'currencies_Id' => $currency->getKey(),
            'account_type' => fake()->randomElement(['checking', 'savings', 'current', 'other']),
            'account_number' => fake()->unique()->numerify('##########'),
            'account_holder' => fake()->company(),
            'swift_code' => fake()->optional()->bothify('????????'),
            'routing_number' => fake()->optional()->numerify('#########'),
            'is_default' => false,
            'is_active' => true,
        ];
    }
}
