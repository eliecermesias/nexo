<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_template_versions', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('document_templates_Id')->constrained('document_templates', 'Id', 'FK_document_templates_document_template_versions')->restrictOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->longText('content');
            $table->json('metadata')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['document_templates_Id', 'version'], 'UK_document_template_versions_template_version');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_template_versions');
    }
};
