<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\PlanEvent;
use App\Models\TorSubmission;
use App\Models\InstructorDocument;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class KabidDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        return [
            [
                'label' => 'Event Dibuat',
                'value' => PlanEvent::where('created_by', $user->id)->count(),
            ],
            [
                'label' => 'Event Pending',
                'value' => PlanEvent::where('created_by', $user->id)
                    ->where('status', 'pending')
                    ->count(),
            ],
            [
                'label' => 'Narasumber Aktif',
                'value' => InstructorDocument::where('status', 'approved')
                    ->distinct('user_id')
                    ->count('user_id'),
            ],
        ];
    }

    public function getSummary(User $user): array
    {
        return [
            'my_events' => PlanEvent::where('created_by', $user->id)->latest()->limit(5)->get(),
            'my_tor'    => TorSubmission::where('created_by', $user->id)->latest()->limit(5)->get(),
        ];
    }

    public function getActivities(User $user): array
    {
        return [
            'upcoming_events' => PlanEvent::where('created_by', $user->id)
                ->orderBy('start_date')
                ->limit(10)
                ->get(),
        ];
    }
}
