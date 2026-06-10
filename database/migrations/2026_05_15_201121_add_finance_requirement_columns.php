<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $itemTables = [
        'quotation_items',
        'proposal_items',
        'collection_account_items',
        'invoice_items',
    ];

    public function up(): void
    {
        Schema::create('service_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->constrained('services', 'Id')->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained('currencies', 'Id')->restrictOnDelete();
            $table->string('pricing_type', 40)->default('fixed');
            $table->decimal('base_price', 15, 2)->default(0);
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['team_id', 'service_id', 'is_active']);
        });

        Schema::create('retention_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 40);
            $table->string('name', 120);
            $table->decimal('rate', 7, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['team_id', 'code']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('category', 120)->nullable()->after('description');
            $table->string('pricing_type', 40)->default('fixed')->after('category');
            $table->foreignId('currency_id')->nullable()->after('unit_price')->constrained('currencies', 'Id')->nullOnDelete();
        });

        foreach ($this->itemTables as $itemTable) {
            Schema::table($itemTable, function (Blueprint $table) {
                $table->foreignId('retention_rate_id')->nullable()->after('taxes_Id')->constrained()->nullOnDelete();
                $table->decimal('retention_rate', 7, 4)->default(0)->after('tax_rate');
                $table->decimal('retention_total', 15, 2)->default(0)->after('retention_rate');
            });
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->itemTables) as $itemTable) {
            Schema::table($itemTable, function (Blueprint $table) {
                $table->dropForeign(['retention_rate_id']);
                $table->dropColumn(['retention_rate_id', 'retention_rate', 'retention_total']);
            });
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn(['category', 'pricing_type', 'currency_id']);
        });

        Schema::dropIfExists('retention_rates');
        Schema::dropIfExists('service_rates');
    }
};
