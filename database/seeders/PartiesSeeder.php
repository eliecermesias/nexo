<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartiesSeeder extends Seeder
{
    public function run(): void
    {
        $nitDocumentType = DocumentType::query()->where('code', 'nit')->firstOrFail();
        $owner = User::query()->firstOrFail();

        $parties = [
            [
                'document_number' => '900.093.735-8',
                'party_type' => 'company',
                'legal_name' => 'CYMETRIA Group SAS',
                'email' => 'docentes@cymetria.com',
                'is_customer' => true,
                'is_supplier' => false,
            ],
            [
                'document_number' => '860.012.336-1',
                'party_type' => 'company',
                'legal_name' => 'INSTITUTO COLOMBIANO DE NORMAS TECNICAS Y CERTIFICACION - ICONTEC',
                'email' => 'proveedorsuroccidente@icontec.org',
                'is_customer' => true,
                'is_supplier' => true,
            ],
        ];

        foreach ($parties as $party) {
            Party::query()->updateOrCreate(
                [
                    'document_types_Id' => $nitDocumentType->getKey(),
                    'document_number' => $party['document_number'],
                ],
                $party + [
                    'team_id' => $owner->currentTeam?->id,
                    'created_by' => $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'document_types_Id' => $nitDocumentType->getKey(),
                    'country' => 'Colombia',
                ],
            );
        }
    }
}
