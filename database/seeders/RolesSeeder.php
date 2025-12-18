<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Developer',
                'slug' => 'developer',
                'level' => 100,
                'can_manage_users' => true,
                'can_create_plans' => true,
                'can_approve_plans' => true,
                'can_create_courses' => true,
                'can_approve_courses' => true,
            ],
            ['name' => 'Direktur',     'slug' => 'director',      'level' => 90, 'can_approve_plans' => true],
            ['name' => 'Kabid Diklat', 'slug' => 'head_training', 'level' => 70, 'can_create_plans' => true],
            ['name' => 'Admin',        'slug' => 'admin',         'level' => 60, 'can_create_courses' => true, 'can_manage_users' => true],
            ['name' => 'Karyawan',      'slug' => 'karyawan',     'level' => 50],
            ['name' => 'Narasumber',   'slug' => 'instructor',    'level' => 40],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['slug' => $r['slug']], $r);
        }
    }
}
