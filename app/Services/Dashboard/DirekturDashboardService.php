<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\AnnualPlan;
use App\Models\PlanEvent;
use App\Models\TorSubmission;
use App\Models\CourseCompletion;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class DirekturDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        return [
            [
                'label' => 'Event Pending',
                'value' => PlanEvent::where('status', 'pending')->count(),
            ],
            [
                'label' => 'TOR Pending',
                'value' => TorSubmission::where('status', 'submitted')->count(),
            ],
            [
                'label' => 'Total Jam Diklat',
                'value' => CourseCompletion::whereYear('completed_at', now()->year)->sum('earned_hours'),
            ],
        ];
    }

    public function getSummary(User $user): array
    {
        return [
            'events_pending' => PlanEvent::where('status', 'pending')->latest()->limit(5)->get(),
            'tor_pending'    => TorSubmission::where('status', 'submitted')->latest()->limit(5)->get(),
        ];
    }

    public function getActivities(User $user): array
    {
        return [
            'events' => PlanEvent::where('status', 'pending')
                ->orderBy('submitted_at', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}
