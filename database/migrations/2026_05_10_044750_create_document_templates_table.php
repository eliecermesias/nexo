<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('enterprises_Id')->constrained('enterprises', 'Id', 'FK_enterprises_document_templates')->restrictOnUpdate()->restrictOnDelete();
            $table->enum('document_kind', ['quotation', 'proposal', 'collection_account', 'invoice']);
            $table->string('name', 180);
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
