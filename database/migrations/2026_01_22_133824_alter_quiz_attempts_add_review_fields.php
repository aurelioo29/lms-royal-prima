<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {

            // reviewer info
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable()
                ->after('submitted_at');

            // upgrade enum status
            $table->enum('status', [
                'started',
                'submitted',
                'expired',
                'reviewed_passed',
                'reviewed_failed',
            ])->default('started')->change();

            // is_passed sekarang NULLABLE
            $table->boolean('is_passed')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {

            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['reviewed_by', 'reviewed_at']);

            $table->enum('status', [
                'started',
                'submitted',
                'expired',
            ])->default('started')->change();

            $table->boolean('is_passed')
                ->default(false)
                ->change();
        });
    }
};
