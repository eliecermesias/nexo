<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_rate');
            $table->string('discount_description', 255)->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'discount_description']);
        });
    }
};
