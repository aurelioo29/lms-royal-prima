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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete()->after('id');

            $table->string('nik')->nullable()->unique()->after('name');
            $table->string('phone')->nullable()->unique()->after('email');
            $table->date('birth_date')->nullable()->after('phone');

            // M = Laki-laki, F = Perempuan
            $table->enum('gender', ['M', 'F'])->nullable()->after('birth_date');

            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->nullOnDelete()->after('gender');
            $table->foreignId('job_title_id')->nullable()->constrained('job_titles')->nullOnDelete()->after('job_category_id');

            $table->boolean('is_active')->default(true)->after('job_title_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropConstrainedForeignId('job_category_id');
            $table->dropConstrainedForeignId('job_title_id');
            $table->dropColumn(['nik', 'phone', 'birth_date', 'gender', 'is_active']);
        });
    }
};
