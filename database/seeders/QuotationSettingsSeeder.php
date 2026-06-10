<?php

namespace Database\Seeders;

use App\Models\DocumentTemplateVersion;
use App\Models\Enterprise;
use App\Models\QuotationSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuotationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        foreach (Enterprise::query()->get() as $enterprise) {
            $defaultTemplateVersionId = DocumentTemplateVersion::query()
                ->whereHas('documentTemplate', function ($query) use ($enterprise): void {
                    $query->where('enterprises_Id', $enterprise->Id)
                        ->where('document_kind', 'quotation')
                        ->where('is_default', true);
                })
                ->where('is_published', true)
                ->orderByDesc('version')
                ->value('Id');

            QuotationSetting::query()->updateOrCreate(
                [
                    'team_id' => $enterprise->team_id ?? $owner->currentTeam?->id,
                    'enterprises_Id' => $enterprise->Id,
                ],
                [
                    'created_by' => $enterprise->created_by ?? $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'document_template_versions_Id' => $defaultTemplateVersionId,
                    'validity_days' => 15,
                    'default_term' => 'Validez de la oferta: 15 dias calendario. Pago contra entrega o segun acuerdo comercial vigente.',
                    'default_note' => 'Documento de trabajo interno. Validar descuentos, impuestos y aprobacion comercial antes del envio.',
                ],
            );
        }
    }
}
