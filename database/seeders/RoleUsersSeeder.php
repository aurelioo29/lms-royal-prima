<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // ambil role id by slug
        $roleIds = DB::table('roles')
            ->whereIn('slug', ['developer', 'director', 'head_training', 'admin', 'instructor'])
            ->pluck('id', 'slug'); // ['developer' => 1, ...]

        $defaults = [
            [
                'slug' => 'developer',
                'name' => 'Developer',
                'email' => 'developer@rs.local',
                'nik' => 'DEV-0001',
            ],
            [
                'slug' => 'director',
                'name' => 'Direktur',
                'email' => 'direktur@rs.local',
                'nik' => 'DIR-0001',
            ],
            [
                'slug' => 'head_training',
                'name' => 'Kabid Diklat',
                'email' => 'diklat@rs.local',
                'nik' => 'KBD-0001',
            ],
            [
                'slug' => 'admin',
                'name' => 'Admin LMS',
                'email' => 'admin@rs.local',
                'nik' => 'ADM-0001',
            ],
            [
                'slug' => 'instructor',
                'name' => 'Narasumber',
                'email' => 'narasumber@rs.local',
                'nik' => 'NSB-0001',
            ],
        ];

        foreach ($defaults as $d) {
            $roleId = $roleIds[$d['slug']] ?? null;

            User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('Password123'),
                    'role_id' => $roleId,

                    // optional fields (sesuaikan kalau kolomnya ada)
                    'nik' => $d['nik'] ?? null,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
