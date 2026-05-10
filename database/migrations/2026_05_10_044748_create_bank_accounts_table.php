<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id', 'FK_enterprises_bank_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('banks_Id')->constrained('banks', 'Id', 'FK_banks_bank_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('currencies_Id')->constrained('currencies', 'Id', 'FK_currencies_bank_accounts')->restrictOnUpdate()->restrictOnDelete();
            $table->enum('account_type', ['checking', 'savings', 'current', 'other'])->default('savings');
            $table->string('account_number', 80);
            $table->string('account_holder', 180);
            $table->string('swift_code', 40)->nullable();
            $table->string('routing_number', 40)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['enterprises_Id', 'account_number'], 'UK_bank_accounts_enterprise_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
