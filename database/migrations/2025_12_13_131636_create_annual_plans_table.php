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

            $table->unsignedSmallInteger('year')->index();
            $table->string('title');
            $table->text('description')->nullable();
            // draft|pending|approved|rejected
            $table->string('status', 20)->default('draft')->index();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejected_reason')->nullable();

            $table->timestamps();

            // kalau kamu mau 1 plan per tahun (strict), uncomment:
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
