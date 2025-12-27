<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditEvent;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class AdminDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        return [
            [
                'label' => 'Total User',
                'value' => User::count(),
            ],
            [
                'label' => 'User Aktif',
                'value' => User::where('is_active', true)->count(),
            ],
            [
                'label' => 'Total Role',
                'value' => Role::count(),
            ],
        ];
    }

    public function getSummary(User $user): array
    {
        return [
            'recent_users' => User::latest()->limit(5)->get(),
        ];
    }

    public function getActivities(User $user): array
    {
        return [
            'audit_logs' => AuditEvent::latest()
                ->whereNotIn('action', ['login'])
                ->limit(10)
                ->get(),
        ];
    }
}
