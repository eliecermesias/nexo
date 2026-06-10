<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        $services = [
            ['code' => 'AUD-001', 'name' => 'Auditoria documental', 'unit' => 'servicio', 'unit_price' => 350000],
            ['code' => 'CAP-001', 'name' => 'Capacitacion especializada', 'unit' => 'hora', 'unit_price' => 180000],
            ['code' => 'ASE-001', 'name' => 'Acompanamiento consultivo', 'unit' => 'mes', 'unit_price' => 1250000],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['code' => $service['code']],
                $service + [
                    'team_id' => $owner->currentTeam?->id,
                    'created_by' => $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'description' => $service['name'],
                    'is_active' => true,
                ],
            );
        }
    }
}
