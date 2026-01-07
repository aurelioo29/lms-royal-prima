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
        Schema::create('course_instructors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // peran di course
            $table->enum('role', ['mentor', 'co_mentor'])
                ->default('mentor');

            // status aktivasi narasumber di course
            $table->enum('status', ['assigned', 'active', 'inactive'])
                ->default('assigned');

            $table->boolean('can_manage_modules')
                ->default(true)
                ->comment('Boleh tambah/edit module');

            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_instructors');
    }
};
