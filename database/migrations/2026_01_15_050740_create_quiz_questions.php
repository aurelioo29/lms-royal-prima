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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_quiz_id')
                ->constrained('module_quizzes')
                ->cascadeOnDelete();

            $table->text('question');

            // mcq | essay | true_false
            $table->enum('type', ['mcq', 'essay', 'true_false'])
                ->index();

            $table->unsignedSmallInteger('score')->default(1);
            $table->unsignedInteger('sort_order')->default(1);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['module_quiz_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
