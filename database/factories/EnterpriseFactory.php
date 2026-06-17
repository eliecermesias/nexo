<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enterprise>
 */
class EnterpriseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $documentType = DocumentType::query()->firstOrCreate(
            ['code' => 'nit'],
            ['name' => 'NIT', 'is_active' => true],
        );

        $country = Country::query()->firstOrCreate(
            ['code' => 'CO'],
            ['name' => 'Colombia', 'is_active' => true],
        );

        $state = State::query()->firstOrCreate(
            ['countries_Id' => $country->getKey(), 'code' => '11'],
            ['name' => 'Cundinamarca', 'is_active' => true],
        );

        $owner = User::query()->first() ?? User::factory()->create();

        return [
            'team_id' => $owner->currentTeam?->id,
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'document_types_Id' => $documentType->getKey(),
            'document_number' => fake()->unique()->numerify('9########'),
            'legal_name' => fake()->unique()->company(),
            'trade_name' => fake()->companySuffix().' '.fake()->word(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('300#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => $state->name,
            'country' => $country->name,
            'countries_Id' => $country->getKey(),
            'states_Id' => $state->getKey(),
            'tax_regime' => fake()->randomElement(['Responsable de IVA', 'No responsable de IVA', 'Regimen simple']),
        ];
    }
}
