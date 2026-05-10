<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('document_types_Id')->constrained('document_types', 'Id', 'FK_document_types_parties')->restrictOnUpdate()->restrictOnDelete();
            $table->string('document_number', 50);
            $table->enum('party_type', ['person', 'company'])->default('company');
            $table->string('legal_name', 180);
            $table->string('trade_name', 180)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('country', 120)->default('Colombia');
            $table->boolean('is_customer')->default(true);
            $table->boolean('is_supplier')->default(false);
            $table->timestamps();

            $table->unique(['document_types_Id', 'document_number'], 'UK_parties_document');
            $table->index('legal_name', 'IDX_parties_legal_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
