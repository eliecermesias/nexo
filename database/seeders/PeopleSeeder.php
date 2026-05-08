<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = new \App\Models\people();
        $person->document_type_id = 1; // Asumiendo que el tipo de documento con ID 1 es "DNI"
        $person->document_number = '16459137';
        $person->name = 'Eliecer';
        $person->lastname = 'Mesias';
        $person->email = 'eliecer.mesias@gmail.com';
        $person->phone = '3175147301';
        $person->address = 'Cr 34 # 13 - 51 Apt 201-1';
        $person->save();
    }
}
