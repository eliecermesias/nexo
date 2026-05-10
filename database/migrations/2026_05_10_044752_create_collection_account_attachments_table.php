<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_account_attachments', function (Blueprint $table) {
            $table->id('Id');
            $table->foreignId('collection_accounts_Id')->constrained('collection_accounts', 'Id', 'FK_collection_accounts_collection_account_attachments')->restrictOnUpdate()->restrictOnDelete();
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
        Schema::dropIfExists('collection_account_attachments');
    }
};
