<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_destinations', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id', 'FK_enterprises_payment_destinations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('payment_methods_Id')->constrained('payment_methods', 'Id', 'FK_payment_methods_payment_destinations')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('bank_accounts_Id')->nullable()->constrained('bank_accounts', 'Id', 'FK_bank_accounts_payment_destinations')->restrictOnUpdate()->restrictOnDelete();
            $table->string('name', 180);
            $table->string('cash_location', 180)->nullable();
            $table->string('check_payee_name', 180)->nullable();
            $table->text('instruction')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_destinations');
    }
};
