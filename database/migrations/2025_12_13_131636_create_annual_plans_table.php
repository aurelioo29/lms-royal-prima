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
        Schema::create('annual_plans', function (Blueprint $table) {
            $table->id();
            $table->year('year');

            // Kabid yang bikin draft
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // submit
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();

            // approval direktur/developer
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('review_notes')->nullable();

            $table->timestamps();

            // 1 rencana per tahun (kalau nanti mau versi, kita ubah jadi versioning)
            $table->unique(['year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_plans');
    }
};
