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
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');

            // jenis materi
            $table->enum('type', ['pdf', 'video', 'link', 'quiz'])->default('pdf');

            // untuk link/video embed/text
            $table->text('content')->nullable();

            // untuk upload file
            $table->string('file_path')->nullable();

            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_required')->default(true);

            $table->timestamps();

            $table->index(['course_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
