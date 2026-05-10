<?php

namespace Tests\Feature;

use Database\Seeders\BanksSeeder;
use Database\Seeders\CurrenciesSeeder;
use Database\Seeders\DocumentStatusesSeeder;
use Database\Seeders\PaymentMethodsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NexoCommercialDatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_commercial_sql_tables_are_created(): void
    {
        $tables = [
            'document_types',
            'enterprises',
            'parties',
            'contacts',
            'currencies',
            'document_statuses',
            'services',
            'plans',
            'plan_items',
            'taxes',
            'quotations',
            'quotation_items',
            'proposals',
            'proposal_items',
            'collection_accounts',
            'collection_account_items',
            'invoices',
            'invoice_items',
            'payment_methods',
            'banks',
            'bank_accounts',
            'payment_destinations',
            'payments',
            'document_templates',
            'document_template_versions',
            'collection_account_attachments',
            'invoice_attachments',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
            $this->assertTrue(Schema::hasColumn($table, 'Id'), "Missing Id column on table: {$table}");
        }
    }

    public function test_catalog_seeders_create_requested_banks_and_currencies(): void
    {
        $this->seed([
            CurrenciesSeeder::class,
            BanksSeeder::class,
            DocumentStatusesSeeder::class,
            PaymentMethodsSeeder::class,
        ]);

        $this->assertDatabaseHas('currencies', ['code' => 'COP']);
        $this->assertDatabaseHas('currencies', ['code' => 'USD']);
        $this->assertDatabaseHas('banks', ['code' => 'BANCOLOMBIA']);
        $this->assertDatabaseHas('banks', ['code' => 'DAVIVIENDA']);
        $this->assertDatabaseHas('payment_methods', ['code' => 'transfer', 'requires_bank_account' => true]);
    }

    public function test_commercial_foreign_keys_use_restrict_on_delete(): void
    {
        $constraints = [
            'FK_document_types_enterprises',
            'FK_document_types_parties',
            'FK_parties_contacts',
            'FK_currencies_quotations',
            'FK_banks_bank_accounts',
            'FK_currencies_bank_accounts',
            'FK_payment_methods_payments',
        ];

        foreach ($constraints as $constraint) {
            $deleteRule = DB::table('information_schema.REFERENTIAL_CONSTRAINTS')
                ->whereRaw('CONSTRAINT_SCHEMA = DATABASE()')
                ->where('CONSTRAINT_NAME', $constraint)
                ->value('DELETE_RULE');

            $this->assertSame('RESTRICT', $deleteRule, "Constraint {$constraint} is not RESTRICT.");
        }
    }
}
