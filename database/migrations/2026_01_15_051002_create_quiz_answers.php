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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_attempt_id')
                ->constrained('quiz_attempts')
                ->cascadeOnDelete();

            $table->foreignId('quiz_question_id')
                ->constrained('quiz_questions')
                ->cascadeOnDelete();

            // untuk mcq / true_false
            $table->foreignId('quiz_question_option_id')
                ->nullable()
                ->constrained('quiz_question_options')
                ->nullOnDelete();

            // untuk essay
            $table->text('answer_text')->nullable();

            $table->unsignedSmallInteger('score')->default(0);
            $table->boolean('is_correct')->default(false);

            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'quiz_question_id']);
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
