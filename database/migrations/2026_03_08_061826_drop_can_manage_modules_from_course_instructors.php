<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('course_instructors', function (Blueprint $table) {
            $table->dropColumn('can_manage_modules');
        });
    }

    public function down(): void
    {
        Schema::table('course_instructors', function (Blueprint $table) {
            $table->boolean('can_manage_modules')
                ->default(true)
                ->comment('Boleh tambah/edit module');
        });
    }
};
