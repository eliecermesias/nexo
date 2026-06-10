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
        Schema::create('quotation_settings', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id')->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('document_template_versions_Id')->nullable()->constrained('document_template_versions', 'Id')->nullOnDelete();
            $table->unsignedSmallInteger('validity_days')->default(15);
            $table->text('default_term')->nullable();
            $table->text('default_note')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'enterprises_Id'], 'UK_quotation_settings_team_enterprise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_settings');
    }
};
