<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id('Id');
            $table->char('code', 2)->unique('UK_countries_code');
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('countries_Id')->constrained('countries', 'Id', 'FK_countries_states')->restrictOnUpdate()->restrictOnDelete();
            $table->string('name', 120);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['countries_Id', 'name'], 'UK_states_country_name');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('states_Id')->constrained('states', 'Id', 'FK_states_cities')->restrictOnUpdate()->restrictOnDelete();
            $table->string('name', 120);
            $table->boolean('is_capital')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['states_Id', 'name'], 'UK_cities_state_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
};
