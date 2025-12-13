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
        Schema::create('instructor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // jenis dokumen: 'mot', 'str', 'sip', dll (nanti bisa tambah)
            $table->string('type', 50)->index();

            // file
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();

            // status verifikasi
            $table->string('status', 20)->default('pending')->index(); // pending|approved|rejected
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();

            $table->foreignId('verified_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('rejected_reason')->nullable();

            $table->timestamps();

            // untuk mencegah 1 user punya 2 dokumen MOT "aktif" dalam 1 waktu
            $table->unique(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_documents');
    }
};
