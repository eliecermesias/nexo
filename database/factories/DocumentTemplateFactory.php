<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentTemplate>
 */
class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

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
            'document_kind' => 'quotation',
            'name' => 'Plantilla '.fake()->unique()->word(),
            'description' => fake()->sentence(),
            'is_default' => false,
            'is_active' => true,
        ];
    }

    public function quotationDefault(): static
    {
        return $this->state(fn () => [
            'document_kind' => 'quotation',
            'is_default' => true,
        ]);
    }
}
