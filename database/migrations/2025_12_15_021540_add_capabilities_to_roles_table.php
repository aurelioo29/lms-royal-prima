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
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('can_create_plans')->default(false)->after('can_manage_users');
            $table->boolean('can_approve_plans')->default(false)->after('can_create_plans');
            $table->boolean('can_create_courses')->default(false)->after('can_approve_plans');
            $table->boolean('can_approve_courses')->default(false)->after('can_create_courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'can_create_plans',
                'can_approve_plans',
                'can_create_courses',
                'can_approve_courses',
            ]);
        });
    }
};
