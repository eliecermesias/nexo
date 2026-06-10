<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentTemplateVersion>
 */
class DocumentTemplateVersionFactory extends Factory
{
    protected $model = DocumentTemplateVersion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_templates_Id' => DocumentTemplate::factory(),
            'version' => 1,
            'content' => '<h1>{{ quotation.number }}</h1>',
            'metadata' => ['label' => fake()->word()],
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}
