<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Eliecer mesias',
            'email' => 'eliecer.mesias@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call([
            DocumentTypesSeeder::class,
            LocationsSeeder::class,
            DocumentStatusesSeeder::class,
            CurrenciesSeeder::class,
            TaxesSeeder::class,
            RetentionRatesSeeder::class,
            InternalSequencesSeeder::class,
            ServicesSeeder::class,
            ServiceRatesSeeder::class,
            PlansSeeder::class,
            PaymentMethodsSeeder::class,
            BanksSeeder::class,
            EnterprisesSeeder::class,
            BankAccountsSeeder::class,
            PaymentDestinationsSeeder::class,
            PartiesSeeder::class,
            ContactsSeeder::class,
            DocumentTemplatesSeeder::class,
            QuotationSettingsSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
