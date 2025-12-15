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
            $table->foreignId('annual_plan_id')->constrained('annual_plans')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->date('date')->index();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('location')->nullable();
            $table->string('target_audience')->nullable(); // optional
            $table->string('status', 20)->default('scheduled')->index(); // scheduled|cancelled|done (optional)

            $table->timestamps();
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
