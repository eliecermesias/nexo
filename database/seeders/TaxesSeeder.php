<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxesSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            ['code' => 'iva_0', 'name' => 'IVA 0%', 'rate' => 0, 'is_active' => true],
            ['code' => 'iva_5', 'name' => 'IVA 5%', 'rate' => 5, 'is_active' => true],
            ['code' => 'iva_19', 'name' => 'IVA 19%', 'rate' => 19, 'is_active' => true],
            ['code' => 'inc_4', 'name' => 'National Consumption Tax 4%', 'rate' => 4, 'is_active' => true],
            ['code' => 'inc_8', 'name' => 'National Consumption Tax 8%', 'rate' => 8, 'is_active' => true],
            ['code' => 'inc_16', 'name' => 'National Consumption Tax 16%', 'rate' => 16, 'is_active' => true],
        ];

        foreach ($taxes as $tax) {
            Tax::query()->updateOrCreate(
                ['code' => $tax['code']],
                $tax,
            );
        }
    }
}
