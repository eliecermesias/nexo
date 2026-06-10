<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_enterprise_id')->nullable()->constrained('enterprises', 'Id')->nullOnDelete();
            $table->string('document_type', 80);
            $table->string('prefix', 40)->nullable();
            $table->string('suffix', 40)->nullable();
            $table->unsignedTinyInteger('padding')->default(0);
            $table->boolean('resets_yearly')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['team_id', 'client_enterprise_id', 'document_type'], 'internal_sequences_scope_document_unique');
        });

        Schema::create('sequence_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_sequence_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('period_year')->nullable();
            $table->unsignedBigInteger('current_value')->default(0);
            $table->timestamps();

            $table->unique(['internal_sequence_id', 'period_year']);
        });

        Schema::create('sequence_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_sequence_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sequence_counter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number', 120);
            $table->unsignedBigInteger('numeric_value');
            $table->nullableMorphs('documentable');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('generated_at');
            $table->timestamps();

            $table->unique(['internal_sequence_id', 'document_number'], 'sequence_histories_sequence_number_unique');
        });

        Schema::create('external_invoice_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_enterprise_id')->constrained('enterprises', 'Id')->restrictOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices', 'Id')->nullOnDelete();
            $table->string('number', 120);
            $table->string('source', 80)->nullable();
            $table->date('issue_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'client_enterprise_id', 'number'], 'external_invoice_numbers_scope_number_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_invoice_numbers');
        Schema::dropIfExists('sequence_histories');
        Schema::dropIfExists('sequence_counters');
        Schema::dropIfExists('internal_sequences');
    }
};
