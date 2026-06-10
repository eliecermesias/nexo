<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_enterprise_id')->constrained('enterprises', 'Id')->restrictOnDelete();
            $table->string('name', 180);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['team_id', 'client_enterprise_id', 'name']);
        });

        Schema::create('compliance_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_matrix_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 80);
            $table->string('name', 180);
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_blocking')->default(true);
            $table->boolean('requires_expiration_date')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('requires_file_upload')->default(true);
            $table->boolean('can_be_generated_by_system')->default(false);
            $table->boolean('must_be_attached_separately')->default(false);
            $table->boolean('must_be_merged_into_final_pdf')->default(true);
            $table->unsignedInteger('merge_order')->default(0);
            $table->string('renewal_frequency', 40)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['compliance_matrix_id', 'document_type', 'name'], 'compliance_requirements_matrix_document_name_unique');
            $table->index(['compliance_matrix_id', 'is_active']);
        });

        Schema::create('compliance_requirement_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_requirement_id');
            $table->string('condition_type', 80);
            $table->json('condition_payload')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['compliance_requirement_id', 'condition_type'], 'compliance_conditions_requirement_type_index');
            $table->foreign('compliance_requirement_id', 'compliance_conditions_requirement_fk')
                ->references('id')
                ->on('compliance_requirements')
                ->cascadeOnDelete();
        });

        Schema::create('compliance_validation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('compliance_matrix_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('documentable', 'compliance_validation_documentable_index');
            $table->string('status', 40)->default('pending');
            $table->unsignedInteger('valid_documents_count')->default(0);
            $table->unsignedInteger('missing_documents_count')->default(0);
            $table->unsignedInteger('expired_documents_count')->default(0);
            $table->unsignedInteger('rejected_documents_count')->default(0);
            $table->boolean('is_blocked')->default(false);
            $table->json('blocking_reasons')->nullable();
            $table->json('suggested_actions')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['team_id', 'status']);
        });

        Schema::create('compliance_validation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compliance_validation_result_id');
            $table->foreignId('compliance_requirement_id');
            $table->string('status', 40)->default('missing');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->unique(['compliance_validation_result_id', 'compliance_requirement_id'], 'compliance_validation_items_result_requirement_unique');
            $table->foreign('compliance_validation_result_id', 'compliance_items_result_fk')
                ->references('id')
                ->on('compliance_validation_results')
                ->cascadeOnDelete();
            $table->foreign('compliance_requirement_id', 'compliance_items_requirement_fk')
                ->references('id')
                ->on('compliance_requirements')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_validation_items');
        Schema::dropIfExists('compliance_validation_results');
        Schema::dropIfExists('compliance_requirement_conditions');
        Schema::dropIfExists('compliance_requirements');
        Schema::dropIfExists('compliance_matrices');
    }
};
