<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        $plans = [
            ['code' => 'BASIC', 'name' => 'Plan básico', 'billing_period' => 'monthly', 'price' => 900000],
            ['code' => 'PRO', 'name' => 'Plan profesional', 'billing_period' => 'monthly', 'price' => 1800000],
            ['code' => 'ENTERPRISE', 'name' => 'Plan empresarial', 'billing_period' => 'annual', 'price' => 18000000],
        ];

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['code' => $plan['code']],
                $plan + [
                    'team_id' => $owner->currentTeam?->id,
                    'created_by' => $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'description' => $plan['name'],
                    'is_active' => true,
                ],
            );
        }
    }
}
