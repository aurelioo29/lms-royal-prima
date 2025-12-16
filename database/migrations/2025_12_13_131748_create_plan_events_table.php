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
            $table->text('description')->nullable();

            // Multi-day (range)
            $table->date('start_date')->index();
            $table->date('end_date')->index();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->string('location')->nullable();
            $table->string('target_audience')->nullable();

            // optional
            $table->string('mode', 20)->nullable(); // online|offline|blended
            $table->string('meeting_link')->nullable();

            // approval flow event
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->index();

            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejected_reason')->nullable();

            $table->timestamps();

            $table->index(['annual_plan_id', 'start_date']);
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
