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

            $table->foreignId('tor_submission_id')->nullable()->constrained('tor_submissions')->nullOnDelete();
            $table->foreignId('course_type_id')->nullable()->constrained('course_types')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('training_hours', 5, 2)->default(0);

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->index();

            // admin pembuat course
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();

            $table->timestamps();
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
