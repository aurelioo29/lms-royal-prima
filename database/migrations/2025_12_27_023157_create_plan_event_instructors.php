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
        Schema::create('plan_event_instructors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plan_event_id')
                ->constrained('plan_events')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('role', ['speaker', 'moderator'])
                ->default('speaker');

            $table->integer('teaching_hours')
                ->default(0)
                ->comment('Jam mengajar narasumber pada event ini');

            $table->enum('status', ['assigned', 'confirmed', 'completed'])
                ->default('assigned');

            $table->timestamps();

            $table->unique(['plan_event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_event_instructors');
    }
};
