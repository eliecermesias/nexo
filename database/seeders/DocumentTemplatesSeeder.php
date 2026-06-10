<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        foreach (Enterprise::query()->get() as $enterprise) {
            $template = DocumentTemplate::query()->updateOrCreate(
                [
                    'enterprises_Id' => $enterprise->Id,
                    'document_kind' => 'quotation',
                    'name' => 'Plantilla comercial base',
                ],
                [
                    'team_id' => $enterprise->team_id ?? $owner->currentTeam?->id,
                    'created_by' => $enterprise->created_by ?? $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'description' => 'Plantilla base para cotizaciones comerciales.',
                    'is_default' => true,
                    'is_active' => true,
                ],
            );

            DocumentTemplateVersion::query()->updateOrCreate(
                [
                    'document_templates_Id' => $template->Id,
                    'version' => 1,
                ],
                [
                    'content' => '<h1>{{ quotation.number }}</h1><p>{{ quotation.party.legal_name }}</p>',
                    'metadata' => ['name' => 'Base'],
                    'is_published' => true,
                    'published_at' => now(),
                ],
            );
        }
    }
}
