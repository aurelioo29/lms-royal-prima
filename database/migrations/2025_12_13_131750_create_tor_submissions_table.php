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
        Schema::create('tor_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('plan_event_id')
                ->constrained('plan_events')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])
                ->default('draft')
                ->index();

            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();

            // Direktur ACC / reject
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->timestamps();

            $table->index(['plan_event_id']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tor_submissions');
    }
};
