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
        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            // kapan terjadi
            $table->timestamp('occurred_at')->useCurrent()->index();

            // actor (siapa yang melakukan)
            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // snapshot biar tetap kebaca meski user/role berubah/hilang
            $table->string('actor_name', 120)->nullable();
            $table->string('actor_role_slug', 60)->nullable();

            // aksi apa
            // contoh: create, update, delete, approve, reject, upload, enroll, complete, login, logout
            $table->string('action', 40)->index();

            // objek apa yang terdampak
            // contoh: Role, User, JobTitle, CalendarEvent, Course, InstructorDocument
            $table->string('entity_type', 80)->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();

            // ringkasan untuk FE table (singkat & manusiawi)
            $table->string('summary', 255);

            // metadata kecil (json): misal field changed, old/new ringkas, alasan reject, dsb
            $table->json('meta')->nullable();

            // request context (optional tapi berguna)
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            // korelasi request (bagus buat tracing)
            $table->uuid('request_id')->nullable()->index();

            $table->timestamps();

            // index gabungan biar filter FE cepat
            $table->index(['entity_type', 'entity_id']);
            $table->index(['actor_id', 'occurred_at']);
            $table->index(['action', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};
