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
        Schema::create('quiz_answer_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_answer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('is_correct')->default(false);
            $table->text('note')->nullable(); // catatan reviewer

            $table->timestamps();

            // 1 jawaban essay hanya punya 1 review aktif
            $table->unique('quiz_answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answer_reviews');
    }
};
