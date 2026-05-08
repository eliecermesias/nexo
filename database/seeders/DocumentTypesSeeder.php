<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            'Cedula de ciudadania',
            'Cedula de extranjeria',
            'Pasaporte',
            'Tarjeta de identidad',
            'Pasaporte',
        ];

        foreach ($documentTypes as $type) {
            \DB::table('document_types')->insert([
                'document_type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
