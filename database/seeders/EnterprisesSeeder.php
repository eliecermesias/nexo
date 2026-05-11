<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Enterprise;
use Illuminate\Database\Seeder;

class EnterprisesSeeder extends Seeder
{
    public function run(): void
    {
        $nitDocumentType = DocumentType::query()->where('code', 'nit')->firstOrFail();
        $country = Country::query()->where('code', 'CO')->firstOrFail();

        $enterprises = [
            [
                'document_number' => '900.093.735-8',
                'legal_name' => 'CYMETRIA Group SAS',
                'email' => 'docentes@cymetria.com',
            ],
            [
                'document_number' => '860.012.336-1',
                'legal_name' => 'INSTITUTO COLOMBIANO DE NORMAS TECNICAS Y CERTIFICACION - ICONTEC',
                'email' => 'proveedorsuroccidente@icontec.org',
            ],
        ];

        foreach ($enterprises as $enterprise) {
            Enterprise::query()->updateOrCreate(
                [
                    'document_types_Id' => $nitDocumentType->getKey(),
                    'document_number' => $enterprise['document_number'],
                ],
                $enterprise + [
                    'document_types_Id' => $nitDocumentType->getKey(),
                    'country' => 'Colombia',
                    'countries_Id' => $country->getKey(),
                ],
            );
        }
    }
}
