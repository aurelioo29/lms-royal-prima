<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Support\RoleMapper;
use Illuminate\Support\Str;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class DashboardService
{
    public static function resolve(User $user): DashboardRoleService
    {
        $role = RoleMapper::map($user->role->slug ?? '');

        return match ($role) {
            'direktur'      => new DirekturDashboardService(),
            'kabid-diklat'  => new KabidDashboardService(),
            'narasumber'    => new NarasumberDashboardService(),
            'karyawan'      => new KaryawanDashboardService(),
            'admin'         => new AdminDashboardService(),
            default         => new KaryawanDashboardService(),
        };
    }
}
