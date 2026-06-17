<?php

namespace Database\Seeders;

use App\Models\RetentionRate;
use App\Models\User;
use Illuminate\Database\Seeder;

class RetentionRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        $retentionRates = [
            ['code' => 'ret_0', 'name' => 'Sin retención', 'rate' => 0],
            ['code' => 'ret_2_5', 'name' => 'Retención 2.5%', 'rate' => 2.5],
            ['code' => 'ret_4', 'name' => 'Retención 4%', 'rate' => 4],
            ['code' => 'ret_11', 'name' => 'Retención 11%', 'rate' => 11],
        ];

        foreach ($retentionRates as $retentionRate) {
            RetentionRate::query()->updateOrCreate(
                [
                    'team_id' => $owner->currentTeam?->id,
                    'code' => $retentionRate['code'],
                ],
                $retentionRate + [
                    'team_id' => $owner->currentTeam?->id,
                    'is_active' => true,
                ],
            );
        }
    }
}
