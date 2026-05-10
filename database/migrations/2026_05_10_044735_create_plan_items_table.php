<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_items', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('plans_Id')->constrained('plans', 'Id', 'FK_plans_plan_items')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('services_Id')->constrained('services', 'Id', 'FK_services_plan_items')->restrictOnUpdate()->restrictOnDelete();
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_items');
    }
};
