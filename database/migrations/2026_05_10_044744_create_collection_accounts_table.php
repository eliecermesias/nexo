<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_accounts', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('proposals_Id')->nullable()->constrained('proposals', 'Id', 'FK_proposals_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id', 'FK_enterprises_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('parties_Id')->constrained('parties', 'Id', 'FK_parties_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('contacts_Id')->nullable()->constrained('contacts', 'Id', 'FK_contacts_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('currencies_Id')->constrained('currencies', 'Id', 'FK_currencies_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('document_statuses_Id')->constrained('document_statuses', 'Id', 'FK_document_statuses_collection_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->string('number', 50);
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_total', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('concept')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['enterprises_Id', 'number'], 'UK_collection_accounts_enterprise_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_accounts');
    }
};
