<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $document_classes = [
            'Collection Account',
            'Invoice',
            'Price quote'
        ];

        foreach ($document_classes as $class) {
            \DB::table('document_classes')->insert([
                'document_class' => $class,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
