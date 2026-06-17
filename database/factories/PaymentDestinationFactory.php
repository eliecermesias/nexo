<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Enterprise;
use App\Models\PaymentDestination;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentDestination>
 */
class PaymentDestinationFactory extends Factory
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
            'enterprises_Id' => Enterprise::factory(),
            'payment_methods_Id' => PaymentMethod::factory(),
            'bank_accounts_Id' => BankAccount::factory(),
            'name' => fake()->unique()->words(3, true),
            'cash_location' => fake()->optional()->city(),
            'check_payee_name' => fake()->optional()->company(),
            'instruction' => fake()->optional()->sentence(),
            'is_default' => false,
            'is_active' => true,
        ];
    }
}
