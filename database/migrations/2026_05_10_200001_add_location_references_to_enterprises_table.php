<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enterprises', function (Blueprint $table) {
            $table->foreignId('countries_Id')->nullable()->after('country')->constrained('countries', 'Id', 'FK_countries_enterprises')->nullOnDelete();
            $table->foreignId('states_Id')->nullable()->after('countries_Id')->constrained('states', 'Id', 'FK_states_enterprises')->nullOnDelete();
            $table->foreignId('cities_Id')->nullable()->after('states_Id')->constrained('cities', 'Id', 'FK_cities_enterprises')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enterprises', function (Blueprint $table) {
            $table->dropForeign('FK_cities_enterprises');
            $table->dropForeign('FK_states_enterprises');
            $table->dropForeign('FK_countries_enterprises');
            $table->dropColumn(['cities_Id', 'states_Id', 'countries_Id']);
        });
    }
};
