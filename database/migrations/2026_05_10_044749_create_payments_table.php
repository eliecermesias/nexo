<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('payment_methods_Id')->constrained('payment_methods', 'Id', 'FK_payment_methods_payments')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('payment_destinations_Id')->constrained('payment_destinations', 'Id', 'FK_payment_destinations_payments')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('collection_accounts_Id')->nullable()->constrained('collection_accounts', 'Id', 'FK_collection_accounts_payments')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('invoices_Id')->nullable()->constrained('invoices', 'Id', 'FK_invoices_payments')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('currencies_Id')->constrained('currencies', 'Id', 'FK_currencies_payments')->restrictOnUpdate()->restrictOnDelete();
            $table->string('reference', 120)->nullable();
            $table->dateTime('paid_at');
            $table->decimal('amount', 15, 2);
            $table->string('payer_name', 180)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE payments ADD CONSTRAINT CK_payments_single_payable CHECK ((collection_accounts_Id IS NOT NULL AND invoices_Id IS NULL) OR (collection_accounts_Id IS NULL AND invoices_Id IS NOT NULL))');
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
