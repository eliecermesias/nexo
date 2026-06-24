<?php

namespace Database\Factories;

use App\Models\InternalSequence;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InternalSequence>
 */
class InternalSequenceFactory extends Factory
{
    protected $model = InternalSequence::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = User::query()->first() ?? User::factory()->create();

        return [
            'team_id' => $owner->currentTeam?->id ?? Team::factory(),
            'client_enterprise_id' => null,
            'document_type' => fake()->randomElement(['quotation', 'proposal', 'collection_account']),
            'prefix' => strtoupper(fake()->bothify('DOC-')),
            'initial_value' => 1,
            'final_value' => null,
            'current_value' => 0,
            'number_length' => 4,
            'number_format' => '{prefix}{number}{suffix}',
            'suffix' => null,
            'padding' => 4,
            'resets_yearly' => false,
            'is_active' => true,
            'created_by' => $owner->getKey(),
        ];
    }
}
