<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_account_id')->nullable()->constrained('collection_accounts', 'Id')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices', 'Id')->nullOnDelete();
            $table->foreignId('template_version_id')->nullable()->constrained('document_template_versions', 'Id')->nullOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->string('disk', 80)->default('local');
            $table->string('path', 500);
            $table->char('hash_sha256', 64)->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('generated_at')->nullable();
            $table->string('status', 40)->default('draft');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'status']);
            $table->index('hash_sha256');
        });

        Schema::create('document_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_account_id')->nullable()->constrained('collection_accounts', 'Id')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices', 'Id')->nullOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->string('disk', 80)->default('local');
            $table->string('path', 500)->nullable();
            $table->char('hash_sha256', 64)->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('generated_at')->nullable();
            $table->string('status', 40)->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'status']);
            $table->index('hash_sha256');
        });

        Schema::create('document_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_package_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('source');
            $table->unsignedInteger('merge_order')->default(0);
            $table->string('label', 180)->nullable();
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            $table->index(['document_package_id', 'merge_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_package_items');
        Schema::dropIfExists('document_packages');
        Schema::dropIfExists('generated_documents');
    }
};
