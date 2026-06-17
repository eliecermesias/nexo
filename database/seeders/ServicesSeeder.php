<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();
        $currency = Currency::query()->where('code', 'COP')->firstOrFail();

        $services = [
            ['code' => 'AUD-001', 'name' => 'Auditoria documental', 'category' => 'Auditoria', 'pricing_type' => 'fixed', 'unit' => 'servicio', 'unit_price' => 350000],
            ['code' => 'CAP-001', 'name' => 'Capacitacion especializada', 'category' => 'Capacitacion', 'pricing_type' => 'hourly', 'unit' => 'hora', 'unit_price' => 180000],
            ['code' => 'ASE-001', 'name' => 'Acompanamiento consultivo', 'category' => 'Consultoria', 'pricing_type' => 'monthly', 'unit' => 'mes', 'unit_price' => 1250000],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['code' => $service['code']],
                $service + [
                    'team_id' => $owner->currentTeam?->id,
                    'created_by' => $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'description' => $service['name'],
                    'currency_id' => $currency->getKey(),
                    'is_active' => true,
                ],
            );
        }
    }
}
