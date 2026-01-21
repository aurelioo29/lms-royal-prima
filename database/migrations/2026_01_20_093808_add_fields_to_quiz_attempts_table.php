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
        Schema::table('quiz_attempts', function (Blueprint $table) {
            // started_at TIDAK BOLEH NULL
            $table->timestamp('started_at')->nullable(false)->change();

            // TAMBAHAN KRITIS
            $table->timestamp('expired_at')->nullable()->after('started_at');

            // state eksplisit (INI PENTING)
            $table->enum('status', [
                'started',
                'submitted',
                'expired'
            ])->default('started')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            //
        });
    }
};
