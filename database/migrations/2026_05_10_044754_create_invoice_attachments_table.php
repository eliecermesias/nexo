<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_attachments', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('invoices_Id')->constrained('invoices', 'Id', 'FK_invoices_invoice_attachments')->restrictOnUpdate()->restrictOnDelete();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('description')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_attachments');
    }
};
