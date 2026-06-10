<?php

namespace Database\Factories;

use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\QuotationSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuotationSetting>
 */
class QuotationSettingFactory extends Factory
{
    protected $model = QuotationSetting::class;

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
            'document_template_versions_Id' => DocumentTemplateVersion::factory(),
            'validity_days' => 15,
            'default_term' => fake()->paragraph(),
            'default_note' => fake()->sentence(),
        ];
    }
}
