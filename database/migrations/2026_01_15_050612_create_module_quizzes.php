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
        Schema::create('module_quizzes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_module_id')
                ->constrained('course_modules')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('passing_score')->default(70); // %
            $table->unsignedSmallInteger('time_limit')->nullable(); // menit

            $table->boolean('is_mandatory')->default(true);

            // draft | active | archived
            $table->enum('status', ['draft', 'active', 'archived'])
                ->default('draft')
                ->index();

            $table->timestamps();

            // 1 modul = 1 quiz
            $table->unique('course_module_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_quizzes');
    }
};
