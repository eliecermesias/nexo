<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $businessTables = [
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

    /** @var list<string> */
    private array $documentTables = [
        'collection_account_attachments',
        'invoice_attachments',
    ];

    public function up(): void
    {
        foreach ($this->businessTables as $businessTable) {
            Schema::table($businessTable, function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->after('Id')->constrained()->nullOnDelete();
                $table->foreignId('created_by')->nullable()->after('team_id')->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            });
        }

        foreach ($this->documentTables as $documentTable) {
            Schema::table($documentTable, function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->after('Id')->constrained()->nullOnDelete();
                $table->foreignId('uploaded_by')->nullable()->after('team_id')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->documentTables) as $documentTable) {
            Schema::table($documentTable, function (Blueprint $table) {
                $table->dropForeign(['uploaded_by']);
                $table->dropForeign(['team_id']);
                $table->dropColumn(['uploaded_by', 'team_id']);
            });
        }

        foreach (array_reverse($this->businessTables) as $businessTable) {
            Schema::table($businessTable, function (Blueprint $table) {
                $table->dropForeign(['updated_by']);
                $table->dropForeign(['created_by']);
                $table->dropForeign(['team_id']);
                $table->dropColumn(['updated_by', 'created_by', 'team_id']);
            });
        }
    }
};
