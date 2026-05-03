<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Enterprises;
use Illuminate\Database\Seeder;

class EnterprisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enterprise = new Enterprises();
        $enterprise->name = "CYMETRIA Group SAS ";
        $enterprise->nit = "900.093.735-8";
        $enterprise->email = "docentes@cymetria.com";
        $enterprise->save();

        $enterprise = new Enterprises();
        $enterprise->name = "INSTITUTO COLOMBIANO DE NORMAS TÉCNICAS Y CERTIFICACIÓN - ICONTEC";
        $enterprise->nit = "860.012.336-1";
        $enterprise->email = "proveedorsuroccidente@icontec.org";
        $enterprise->save();
    }
}
