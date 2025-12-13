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
        Schema::create('plan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annual_plan_id')->constrained('annual_plans')->cascadeOnDelete();

            $table->string('title');
            $table->string('category')->nullable();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->unsignedInteger('duration_minutes')->default(60);

            $table->string('location')->nullable();

            // narasumber (user role instructor), opsional
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->boolean('is_mandatory')->default(false);

            // ini diset true saat annual_plan approved
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->index(['starts_at']);
            $table->index(['annual_plan_id', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_events');
    }
};
