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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Course must be based on TOR
            $table->foreignId('tor_submission_id')
                ->constrained('tor_submissions')
                ->cascadeOnDelete();

            $table->foreignId('course_type_id')
                ->nullable()
                ->constrained('course_types')
                ->nullOnDelete();

            // NEW fields
            $table->text('tujuan')->nullable();
            $table->decimal('training_hours', 5, 2)->default(0);

            // Enrollment key (unique)
            $table->string('enrollment_key', 32)->unique();

            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft')
                ->index();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            // 1 TOR = 1 Course (recommended)
            $table->unique('tor_submission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
