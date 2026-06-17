<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Enterprise;
use App\Models\PaymentDestination;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentDestinationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::query()->firstOrFail();
        $enterprise = Enterprise::query()->firstOrFail();
        $transfer = PaymentMethod::query()->where('code', 'transfer')->firstOrFail();
        $bankAccount = BankAccount::query()->first();

        PaymentDestination::query()->updateOrCreate(
            [
                'enterprises_Id' => $enterprise->getKey(),
                'name' => 'Transferencia principal',
            ],
            [
                'team_id' => $owner->currentTeam?->id,
                'created_by' => $owner->getKey(),
                'updated_by' => $owner->getKey(),
                'payment_methods_Id' => $transfer->getKey(),
                'bank_accounts_Id' => $bankAccount?->getKey(),
                'cash_location' => null,
                'check_payee_name' => null,
                'instruction' => 'Usar esta cuenta para transferencias bancarias nacionales.',
                'is_default' => true,
                'is_active' => true,
            ],
        );
    }
}
