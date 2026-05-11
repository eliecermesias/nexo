<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->string('code', 2)->nullable()->after('countries_Id');
            $table->unique(['countries_Id', 'code'], 'UK_states_country_code');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->string('code', 5)->nullable()->after('states_Id');
            $table->string('type', 80)->default('Municipio')->after('name');
            $table->decimal('longitude', 10, 6)->nullable()->after('type');
            $table->decimal('latitude', 9, 6)->nullable()->after('longitude');
            $table->unique(['states_Id', 'code'], 'UK_cities_state_code');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique('UK_cities_state_code');
            $table->dropColumn(['code', 'type', 'longitude', 'latitude']);
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropUnique('UK_states_country_code');
            $table->dropColumn('code');
        });
    }
};
