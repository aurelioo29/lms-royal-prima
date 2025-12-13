<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $health = DB::table('job_categories')->updateOrInsert(
            ['slug' => 'tenaga-kesehatan'],
            ['name' => 'Karyawan / Tenaga Kesehatan', 'slug' => 'tenaga-kesehatan']
        );

        $non = DB::table('job_categories')->updateOrInsert(
            ['slug' => 'non-medis'],
            ['name' => 'Karyawan Non Medis', 'slug' => 'non-medis']
        );

        $healthId = DB::table('job_categories')->where('slug', 'tenaga-kesehatan')->value('id');
        $nonId    = DB::table('job_categories')->where('slug', 'non-medis')->value('id');

        $healthTitles = ['Perawat', 'Bidan', 'Dokter Umum', 'Dokter Spesialis', 'Tenaga Farmasi', 'Analis Lab', 'Radiografer', 'Fisioterapis'];
        foreach ($healthTitles as $t) {
            DB::table('job_titles')->updateOrInsert(['job_category_id' => $healthId, 'name' => $t], []);
        }

        $nonTitles = ['Administrasi', 'HR', 'Keuangan', 'IT', 'CS / Front Office', 'Security'];
        foreach ($nonTitles as $t) {
            DB::table('job_titles')->updateOrInsert(['job_category_id' => $nonId, 'name' => $t], []);
        }
    }
}
