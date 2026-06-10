<?php

namespace Database\Factories;

use App\Models\DocumentType;
use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Party>
 */
class PartyFactory extends Factory
{
    protected $model = Party::class;

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

        $owner = User::query()->first() ?? User::factory()->create();

        return [
            'team_id' => $owner->currentTeam?->id,
            'created_by' => $owner->getKey(),
            'updated_by' => $owner->getKey(),
            'document_types_Id' => $documentType->getKey(),
            'document_number' => fake()->unique()->numerify('9#########'),
            'party_type' => 'company',
            'legal_name' => fake()->unique()->company(),
            'trade_name' => fake()->companySuffix().' '.fake()->word(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('300#######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Colombia',
            'is_customer' => true,
            'is_supplier' => false,
        ];
    }
}
