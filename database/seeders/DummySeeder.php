<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummySeeder extends Seeder
{


    public function run(): void
    {


        /**
         * ROLES
         */
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Direktur', 'slug' => 'direktur', 'level' => 5, 'can_manage_users' => true],
            ['id' => 2, 'name' => 'Kabid Diklat', 'slug' => 'kabid-diklat', 'level' => 4, 'can_manage_users' => true],
            ['id' => 3, 'name' => 'Narasumber', 'slug' => 'narasumber', 'level' => 3, 'can_manage_users' => false],
            ['id' => 4, 'name' => 'Karyawan', 'slug' => 'karyawan', 'level' => 1, 'can_manage_users' => false],
            ['id' => 5, 'name' => 'Admin', 'slug' => 'admin', 'level' => 5, 'can_manage_users' => true],
        ]);

        /**
         * JOB CATEGORIES
         */
        DB::table('job_categories')->insert([
            ['id' => 1, 'name' => 'Medis', 'slug' => 'medis'],
            ['id' => 2, 'name' => 'Keperawatan', 'slug' => 'keperawatan'],
            ['id' => 3, 'name' => 'Manajemen', 'slug' => 'manajemen'],
        ]);

        /**
         * JOB TITLES
         */
        DB::table('job_titles')->insert([
            ['id' => 1, 'job_category_id' => 1, 'name' => 'Dokter Umum', 'slug' => 'dokter-umum'],
            ['id' => 2, 'job_category_id' => 2, 'name' => 'Perawat', 'slug' => 'perawat'],
            ['id' => 3, 'job_category_id' => 3, 'name' => 'Staf Diklat', 'slug' => 'staf-diklat'],
        ]);

        /**
         * USERS
         */
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Direktur RS',
                'email' => 'direktur@rs.test',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'nik' => '1111111111',
                'phone' => '0811111111',
                'gender' => 'M',
                'job_category_id' => 3,
                'job_title_id' => 3,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Kabid Diklat',
                'email' => 'diklat@rs.test',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'nik' => '2222222222',
                'phone' => '0822222222',
                'gender' => 'F',
                'job_category_id' => 3,
                'job_title_id' => 3,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Dr. Narasumber',
                'email' => 'narasumber@rs.test',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'nik' => '3333333333',
                'phone' => '0833333333',
                'gender' => 'M',
                'job_category_id' => 1,
                'job_title_id' => 1,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'Perawat Lina',
                'email' => 'karyawan@rs.test',
                'password' => Hash::make('password'),
                'role_id' => 4,
                'nik' => '4444444444',
                'phone' => '0844444444',
                'gender' => 'F',
                'job_category_id' => 2,
                'job_title_id' => 2,
                'is_active' => true,
            ],
        ]);

        /**
         * ANNUAL PLANS
         */
        DB::table('annual_plans')->insert([
            [
                'id' => 1,  // Tambahkan ini setelah ubah migration
                'year' => 2025,
                'created_by' => 2,
                'submitted_by' => 2,
                'submitted_at' => now(),
                'reviewed_by' => 1,
                'reviewed_at' => now(),
                'status' => 'approved',
                'review_notes' => 'Disetujui Direktur',
            ],
        ]);

        /**
         * PLAN EVENTS
         */
        DB::table('plan_events')->insert([
            [
                'id' => 1,
                'annual_plan_id' => 1,  // Ganti dari 2025 ke ID
                'title' => 'Pelatihan PPI Dasar',
                'category' => 'Diklat Wajib',
                'starts_at' => Carbon::parse('2025-02-10 08:00'),
                'ends_at' => Carbon::parse('2025-02-10 12:00'),
                'duration_minutes' => 240,
                'location' => 'Aula RS',
                'instructor_id' => 3,
                'is_mandatory' => true,
                'is_published' => true,
            ],
        ]);

        /**
         * COURSES
         */
        DB::table('courses')->insert([
            [
                'id' => 1,
                'title' => 'PPI Dasar Online',
                'description' => 'Pelatihan pencegahan infeksi',
                'training_hours' => 2.5,
                'status' => 'published',
                'created_by' => 2,
            ],
        ]);

        /**
         * COURSE MODULES
         */
        DB::table('course_modules')->insert([
            [
                'id' => 1,
                'course_id' => 1,
                'title' => 'Pengenalan PPI',
                'type' => 'video',
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'course_id' => 1,
                'title' => 'Prosedur PPI',
                'type' => 'pdf',
                'sort_order' => 2,
            ],
        ]);

        /**
         * COURSE ENROLLMENTS
         */
        DB::table('course_enrollments')->insert([
            [
                'id' => 1,
                'course_id' => 1,
                'user_id' => 4,
                'status' => 'completed',
                'enrolled_at' => now(),
                'completed_at' => now(),
            ],
        ]);

        /**
         * MODULE PROGRESS
         */
        DB::table('module_progress')->insert([
            [
                'course_module_id' => 1,
                'user_id' => 4,
                'status' => 'completed',
                'completed_at' => now(),
            ],
            [
                'course_module_id' => 2,
                'user_id' => 4,
                'status' => 'completed',
                'completed_at' => now(),
            ],
        ]);

        /**
         * COURSE COMPLETIONS
         */
        DB::table('course_completions')->insert([
            [
                'course_id' => 1,
                'user_id' => 4,
                'earned_hours' => 2.5,
                'completed_at' => now(),
                'certificate_number' => 'CERT-2025-001',
            ],
        ]);

        /**
         * TOR SUBMISSIONS
         */
        DB::table('tor_submissions')->insert([
            [
                'title' => 'TOR Pelatihan PPI',
                'content' => 'TOR pelatihan wajib RS',
                'status' => 'approved',
                'created_by' => 2,
                'reviewed_by' => 1,
                'submitted_at' => now(),
                'reviewed_at' => now(),
            ],
        ]);

        /**
         * INSTRUCTOR DOCUMENTS (MOT)
         */
        DB::table('instructor_documents')->insert([
            [
                'user_id' => 3,
                'type' => 'mot',
                'file_path' => 'documents/mot_narasumber.pdf',
                'status' => 'approved',
                'verified_by' => 1,
                'verified_at' => now(),
            ],
        ]);

        /**
         * AUDIT EVENTS
         */
        DB::table('audit_events')->insert([
            [
                'actor_id' => 2,
                'actor_name' => 'Kabid Diklat',
                'actor_role_slug' => 'kabid-diklat',
                'action' => 'CREATE',
                'entity_type' => 'AnnualPlan',
                'entity_id' => 1,  // Ganti dari 2025 ke ID
                'summary' => 'Membuat annual plan 2025',
                'ip' => '127.0.0.1',
            ],
            [
                'actor_id' => 1,
                'actor_name' => 'Direktur RS',
                'actor_role_slug' => 'direktur',
                'action' => 'APPROVE',
                'entity_type' => 'AnnualPlan',
                'entity_id' => 1,  // Ganti dari 2025 ke ID
                'summary' => 'Menyetujui annual plan 2025',
                'ip' => '127.0.0.1',
            ],
        ]);
    }
}
