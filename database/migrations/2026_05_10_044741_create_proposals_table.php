<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('quotations_Id')->nullable()->constrained('quotations', 'Id', 'FK_quotations_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id', 'FK_enterprises_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('parties_Id')->constrained('parties', 'Id', 'FK_parties_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('contacts_Id')->nullable()->constrained('contacts', 'Id', 'FK_contacts_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('currencies_Id')->constrained('currencies', 'Id', 'FK_currencies_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('document_statuses_Id')->constrained('document_statuses', 'Id', 'FK_document_statuses_proposals')->restrictOnUpdate()->restrictOnDelete();
            $table->string('number', 50);
            $table->string('title', 180);
            $table->date('issue_date');
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('scope')->nullable();
            $table->text('term')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['enterprises_Id', 'number'], 'UK_proposals_enterprise_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
