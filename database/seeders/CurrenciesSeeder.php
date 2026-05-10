<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'COP', 'name' => 'Pesos Colombianos', 'symbol' => '$', 'decimal_place' => 2],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => 'US$', 'decimal_place' => 2],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => 'EUR', 'decimal_place' => 2],
        ];

        foreach ($currencies as $currency) {
            Currency::query()->updateOrCreate(
                ['code' => $currency['code']],
                $currency,
            );
        }
    }
}
