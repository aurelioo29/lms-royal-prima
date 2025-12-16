<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plan_events', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('annual_plan_id')->constrained('courses')->nullOnDelete();
            $table->string('mode', 20)->nullable()->after('location'); // online|offline|blended
            $table->string('meeting_link')->nullable()->after('mode');
            // kalau kamu mau "target_audience" lebih rapi nanti bisa pindah ke pivot/enum,
            // tapi untuk sekarang biarin dulu.
        });
    }

    public function down(): void
    {
        Schema::table('plan_events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
            $table->dropColumn(['mode', 'meeting_link']);
        });
    }
};
