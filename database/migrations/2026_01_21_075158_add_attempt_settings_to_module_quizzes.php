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
        Schema::table('module_quizzes', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_attempts')
                ->nullable()
                ->after('time_limit')
                ->comment('NULL = unlimited attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_quizzes', function (Blueprint $table) {
            $table->dropColumn('max_attempts');
        });
    }
};
