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
            ['name' => 'Developer',    'slug' => 'developer',     'level' => 100],
            ['name' => 'Direktur',     'slug' => 'director',      'level' => 90],
            ['name' => 'Kabid Diklat', 'slug' => 'head_training', 'level' => 70],
            ['name' => 'Admin',        'slug' => 'admin',         'level' => 60],
            ['name' => 'Narasumber',   'slug' => 'instructor',    'level' => 50],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['slug' => $r['slug']], $r);
        }
    }
}
