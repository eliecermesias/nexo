<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('Id');
            $table->char('code', 3)->unique('UK_currencies_code');
            $table->string('name', 80);
            $table->string('symbol', 10);
            $table->unsignedTinyInteger('decimal_place')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
