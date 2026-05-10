<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypesSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypes = [
            ['code' => 'cc', 'name' => 'Cedula de ciudadania', 'description' => 'Documento de identificacion civil colombiano.'],
            ['code' => 'ce', 'name' => 'Cedula de extranjeria', 'description' => 'Documento de identificacion para residentes extranjeros.'],
            ['code' => 'passport', 'name' => 'Pasaporte', 'description' => 'Documento internacional de viaje.'],
            ['code' => 'ti', 'name' => 'Tarjeta de identidad', 'description' => 'Documento de identificacion para menores de edad.'],
            ['code' => 'nit', 'name' => 'NIT', 'description' => 'Numero de identificacion tributaria colombiano.'],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::query()->updateOrCreate(
                ['code' => $documentType['code']],
                [
                    'name' => $documentType['name'],
                    'description' => $documentType['description'],
                    'is_active' => true,
                ],
            );
        }
    }
}
