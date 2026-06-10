<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploaded_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('client_enterprise_id')->nullable()->constrained('enterprises', 'Id')->nullOnDelete();
            $table->foreignId('compliance_requirement_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('documentable');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('disk', 80)->default('local');
            $table->string('path', 500);
            $table->string('mime_type', 120);
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->char('hash_sha256', 64)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 40)->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'status']);
            $table->index(['client_enterprise_id', 'status']);
            $table->index('hash_sha256');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_documents');
    }
};
