<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NexoRequirementsSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_tables_have_owner_scope_columns(): void
    {
        $tables = [
            'enterprises',
            'parties',
            'contacts',
            'services',
            'plans',
            'quotations',
            'proposals',
            'collection_accounts',
            'invoices',
            'payments',
            'bank_accounts',
            'payment_destinations',
            'document_templates',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'team_id'), "Missing team_id on {$table}");
            $this->assertTrue(Schema::hasColumn($table, 'created_by'), "Missing created_by on {$table}");
            $this->assertTrue(Schema::hasColumn($table, 'updated_by'), "Missing updated_by on {$table}");
        }
    }

    public function test_compliance_and_document_management_tables_exist(): void
    {
        $tables = [
            'compliance_matrices',
            'compliance_requirements',
            'compliance_requirement_conditions',
            'compliance_validation_results',
            'compliance_validation_items',
            'uploaded_documents',
            'generated_documents',
            'document_packages',
            'document_package_items',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }

        $this->assertTrue(Schema::hasColumn('uploaded_documents', 'hash_sha256'));
        $this->assertTrue(Schema::hasColumn('uploaded_documents', 'expires_at'));
        $this->assertTrue(Schema::hasColumn('uploaded_documents', 'approved_by'));
        $this->assertTrue(Schema::hasColumn('document_package_items', 'merge_order'));
    }

    public function test_sequence_audit_and_finance_tables_exist(): void
    {
        $tables = [
            'internal_sequences',
            'sequence_counters',
            'sequence_histories',
            'external_invoice_numbers',
            'activity_logs',
            'audit_logs',
            'service_rates',
            'retention_rates',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }

        $this->assertTrue(Schema::hasColumn('services', 'pricing_type'));
        $this->assertTrue(Schema::hasColumn('invoice_items', 'retention_total'));
        $this->assertTrue(Schema::hasColumn('external_invoice_numbers', 'number'));
    }
}
