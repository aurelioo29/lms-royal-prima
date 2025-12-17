<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RemainingDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan role (sinkron dengan seeder lama)
        $users = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->pluck('users.id', 'roles.slug');

        $kabid      = $users['head_training'];
        $director   = $users['director'];
        $admin      = $users['admin'];
        $instructor = $users['instructor'];
        $karyawan   = $users['karyawan'];

        // Annual Plan (1 per tahun)
        $planId = DB::table('annual_plans')->insertGetId([
            'year'          => now()->year,
            'title'         => 'Rencana Diklat Tahunan ' . now()->year,
            'description'   => 'Rencana kegiatan pendidikan dan pelatihan pegawai rumah sakit selama satu tahun',
            'status'        => 'approved',
            'created_by'    => $kabid,
            'submitted_at'  => now()->subDays(20),
            'approved_by'   => $director,
            'approved_at'   => now()->subDays(15),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Plan Events (3 kegiatan)
        $events = [
            [
                'title' => 'Pelatihan Keselamatan Pasien',
                'desc'  => 'Peningkatan pemahaman keselamatan pasien',
                'days'  => 30,
            ],
            [
                'title' => 'Pelatihan Pencegahan Infeksi',
                'desc'  => 'Standar PPI bagi tenaga kesehatan',
                'days'  => 60,
            ],
            [
                'title' => 'Pelatihan Manajemen Mutu Rumah Sakit',
                'desc'  => 'Peningkatan mutu layanan RS',
                'days'  => 90,
            ],
        ];

        $eventIds = [];

        foreach ($events as $e) {
            $eventIds[] = DB::table('plan_events')->insertGetId([
                'annual_plan_id' => $planId,
                'title'          => $e['title'],
                'description'    => $e['desc'],
                'start_date'     => now()->addDays($e['days'])->toDateString(),
                'end_date'       => now()->addDays($e['days'] + 1)->toDateString(),
                'mode'           => 'offline',
                'status'         => 'approved',
                'created_by'     => $kabid,
                'approved_by'    => $director,
                'approved_at'    => now()->subDays(10),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // TOR Submissions
        $torIds = [];

        foreach ($eventIds as $eid) {
            $torIds[] = DB::table('tor_submissions')->insertGetId([
                'plan_event_id' => $eid,
                'title'         => 'TOR ' . DB::table('plan_events')->where('id', $eid)->value('title'),
                'status'        => 'approved',
                'created_by'    => $kabid,
                'reviewed_by'   => $director,
                'submitted_at'  => now()->subDays(14),
                'reviewed_at'   => now()->subDays(12),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Course Type (1)
        $courseTypeId = DB::table('course_types')->updateOrInsert(
            ['slug' => 'pelatihan-wajib'],
            [
                'name'        => 'Pelatihan Wajib',
                'slug'        => 'pelatihan-wajib',
                'description' => 'Pelatihan internal wajib bagi pegawai',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        $courseTypeId = DB::table('course_types')->where('slug', 'pelatihan-wajib')->value('id');

        // Courses (1 course = 1 TOR)
        $courseIds = [];

        foreach ($torIds as $torId) {
            $courseIds[] = DB::table('courses')->insertGetId([
                'tor_submission_id' => $torId,
                'course_type_id'    => $courseTypeId,
                'title'             => 'Course ' . DB::table('tor_submissions')->where('id', $torId)->value('title'),
                'training_hours'    => 3,
                'status'            => 'published',
                'created_by'        => $admin,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // Course Modules (2 per course)
        foreach ($courseIds as $cid) {
            DB::table('course_modules')->insert([
                [
                    'course_id'  => $cid,
                    'title'      => 'Materi Utama',
                    'type'       => 'pdf',
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'course_id'  => $cid,
                    'title'      => 'Evaluasi',
                    'type'       => 'quiz',
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // Enrollment & Completion
        foreach ($courseIds as $cid) {
            DB::table('course_enrollments')->insert([
                'course_id'    => $cid,
                'user_id'      => $karyawan,
                'status'       => 'completed',
                'enrolled_at'  => now()->subDays(5),
                'completed_at' => now()->subDays(1),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            DB::table('course_completions')->insert([
                'course_id'          => $cid,
                'user_id'            => $karyawan,
                'earned_hours'       => 3,
                'completed_at'       => now()->subDays(1),
                'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        // Instructor MOT Document
        DB::table('instructor_documents')->insert([
            'user_id'     => $instructor,
            'type'        => 'mot',
            'file_path'   => 'documents/mot_narasumber.pdf',
            'status'      => 'approved',
            'verified_by' => $director,
            'verified_at' => now()->subDays(7),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Audit Log
        DB::table('audit_events')->insert([
            'occurred_at'      => now()->subDays(15),
            'actor_id'         => $director,
            'actor_name'       => 'Direktur',
            'actor_role_slug'  => 'director',
            'action'           => 'approve',
            'entity_type'      => 'AnnualPlan',
            'entity_id'        => $planId,
            'summary'          => 'Persetujuan rencana diklat tahunan',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
