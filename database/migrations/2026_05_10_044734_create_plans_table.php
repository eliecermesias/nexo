<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id('Id');
            $table->string('code', 50)->unique('UK_plans_code');
            $table->string('name', 180);
            $table->text('description')->nullable();
            $table->enum('billing_period', ['one_time', 'monthly', 'quarterly', 'semiannual', 'annual'])->default('monthly');
            $table->decimal('price', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
