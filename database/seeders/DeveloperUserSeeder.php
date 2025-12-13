<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeveloperUserSeeder extends Seeder
{
    public function run(): void
    {
        $roleId = DB::table('roles')->where('slug', 'developer')->value('id');

        User::updateOrCreate(
            ['email' => 'developer@rs.local'],
            [
                'name' => 'Developer',
                'password' => Hash::make('Password123'),
                'role_id' => $roleId,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
