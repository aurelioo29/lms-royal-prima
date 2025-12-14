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
        Schema::create('job_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_category_id')->constrained('job_categories')->cascadeOnDelete()->restrictOnDelete();
            $table->string('name', 120);
            $table->string('slug', 120)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['job_category_id', 'name', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_titles');
    }
};
