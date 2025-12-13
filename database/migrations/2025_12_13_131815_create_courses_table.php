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
            $table->string('title');
            $table->text('description')->nullable();

            // total jam diklat dari course (misal 1.50 jam)
            $table->decimal('training_hours', 5, 2)->default(0);

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            $table->index(['status']);
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
