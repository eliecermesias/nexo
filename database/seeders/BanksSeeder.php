<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BanksSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['code' => 'BANCOLOMBIA', 'name' => 'Bancolombia', 'country' => 'Colombia'],
            ['code' => 'DAVIVIENDA', 'name' => 'Banco Davivienda', 'country' => 'Colombia'],
            ['code' => 'BOGOTA', 'name' => 'Banco de Bogota', 'country' => 'Colombia'],
            ['code' => 'BBVA_CO', 'name' => 'BBVA Colombia', 'country' => 'Colombia'],
            ['code' => 'OCCIDENTE', 'name' => 'Banco de Occidente', 'country' => 'Colombia'],
            ['code' => 'POPULAR', 'name' => 'Banco Popular', 'country' => 'Colombia'],
            ['code' => 'AV_VILLAS', 'name' => 'Banco AV Villas', 'country' => 'Colombia'],
            ['code' => 'COLPATRIA', 'name' => 'Scotiabank Colpatria', 'country' => 'Colombia'],
            ['code' => 'ITAU_CO', 'name' => 'Itau Colombia', 'country' => 'Colombia'],
            ['code' => 'CAJA_SOCIAL', 'name' => 'Banco Caja Social', 'country' => 'Colombia'],
            ['code' => 'AGRARIO', 'name' => 'Banco Agrario de Colombia', 'country' => 'Colombia'],
            ['code' => 'GNB_SUDAMERIS', 'name' => 'Banco GNB Sudameris', 'country' => 'Colombia'],
            ['code' => 'PICHINCHA_CO', 'name' => 'Banco Pichincha Colombia', 'country' => 'Colombia'],
            ['code' => 'FALABELLA_CO', 'name' => 'Banco Falabella Colombia', 'country' => 'Colombia'],
            ['code' => 'SERFINANZA', 'name' => 'Banco Serfinanza', 'country' => 'Colombia'],
        ];

        foreach ($banks as $bank) {
            Bank::query()->updateOrCreate(
                ['code' => $bank['code']],
                $bank,
            );
        }
    }
}
