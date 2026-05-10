<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            ['code' => 'cash', 'name' => 'Cash', 'requires_bank_account' => false, 'is_active' => true],
            ['code' => 'check', 'name' => 'Check', 'requires_bank_account' => false, 'is_active' => true],
            ['code' => 'transfer', 'name' => 'Bank Transfer', 'requires_bank_account' => true, 'is_active' => true],
            ['code' => 'card', 'name' => 'Card', 'requires_bank_account' => false, 'is_active' => true],
        ];

        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::query()->updateOrCreate(
                ['code' => $paymentMethod['code']],
                $paymentMethod,
            );
        }
    }
}
