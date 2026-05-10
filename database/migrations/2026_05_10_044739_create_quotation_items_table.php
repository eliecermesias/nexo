<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('quotations_Id')->constrained('quotations', 'Id', 'FK_quotations_quotation_items')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('services_Id')->nullable()->constrained('services', 'Id', 'FK_services_quotation_items')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('plans_Id')->nullable()->constrained('plans', 'Id', 'FK_plans_quotation_items')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('taxes_Id')->nullable()->constrained('taxes', 'Id', 'FK_taxes_quotation_items')->restrictOnUpdate()->restrictOnDelete();
            $table->text('description');
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount_rate', 7, 4)->default(0);
            $table->decimal('tax_rate', 7, 4)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
