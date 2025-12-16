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
        Schema::create('plan_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annual_plan_id')
                ->constrained('annual_plans')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // event menunjuk ke course (materi)
            $table->foreignId('course_id')
                ->constrained('courses')
                ->restrictOnDelete() // cegah course dihapus kalau sudah dijadwalkan
                ->cascadeOnUpdate();

            $table->string('mode', 20)->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('status', 20)->default('scheduled')->index(); // scheduled|cancelled|done
            $table->string('target_audience')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['annual_plan_id']);
            $table->index(['course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_events');
    }
};
