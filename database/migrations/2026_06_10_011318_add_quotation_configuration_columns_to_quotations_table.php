<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('document_template_versions_Id')
                ->nullable()
                ->after('document_statuses_Id')
                ->constrained('document_template_versions', 'Id', 'FK_document_template_versions_quotations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign('FK_document_template_versions_quotations');
            $table->dropColumn('document_template_versions_Id');
        });
    }
};
