<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Service;
use App\Models\ServiceRate;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::query()->firstOrFail();
        $currency = Currency::query()->where('code', 'COP')->firstOrFail();

        foreach (Service::query()->get() as $service) {
            ServiceRate::query()->updateOrCreate(
                [
                    'service_id' => $service->getKey(),
                    'currency_id' => $currency->getKey(),
                    'starts_on' => now()->startOfYear()->toDateString(),
                ],
                [
                    'team_id' => $owner->currentTeam?->id,
                    'pricing_type' => $service->pricing_type ?? 'fixed',
                    'base_price' => $service->unit_price,
                    'ends_on' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
