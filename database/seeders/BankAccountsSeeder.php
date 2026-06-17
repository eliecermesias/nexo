<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::query()->firstOrFail();
        $enterprise = Enterprise::query()->firstOrFail();
        $bank = Bank::query()->where('code', 'BANCOLOMBIA')->firstOrFail();
        $currency = Currency::query()->where('code', 'COP')->firstOrFail();

        BankAccount::query()->updateOrCreate(
            [
                'enterprises_Id' => $enterprise->getKey(),
                'account_number' => '000123456789',
            ],
            [
                'team_id' => $owner->currentTeam?->id,
                'created_by' => $owner->getKey(),
                'updated_by' => $owner->getKey(),
                'banks_Id' => $bank->getKey(),
                'currencies_Id' => $currency->getKey(),
                'account_type' => 'savings',
                'account_holder' => $enterprise->legal_name,
                'swift_code' => null,
                'routing_number' => null,
                'is_default' => true,
                'is_active' => true,
            ],
        );
    }
}
