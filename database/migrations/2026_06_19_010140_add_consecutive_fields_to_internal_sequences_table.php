<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internal_sequences', function (Blueprint $table) {
            $table->unsignedBigInteger('initial_value')->default(1)->after('prefix');
            $table->unsignedBigInteger('final_value')->nullable()->after('initial_value');
            $table->unsignedBigInteger('current_value')->default(0)->after('final_value');
            $table->unsignedSmallInteger('number_length')->default(4)->after('current_value');
            $table->string('number_format', 120)->default('{prefix}{number}{suffix}')->after('number_length');
        });

        DB::statement(<<<'SQL'
            UPDATE internal_sequences
            SET current_value = (
                SELECT COALESCE(MAX(sequence_counters.current_value), 0)
                FROM sequence_counters
                WHERE sequence_counters.internal_sequence_id = internal_sequences.id
            )
            WHERE EXISTS (
                SELECT 1
                FROM sequence_counters
                WHERE sequence_counters.internal_sequence_id = internal_sequences.id
            )
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_sequences', function (Blueprint $table) {
            $table->dropColumn([
                'initial_value',
                'final_value',
                'current_value',
                'number_length',
                'number_format',
            ]);
        });
    }
};
