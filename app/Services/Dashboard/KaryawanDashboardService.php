<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\CourseEnrollment;
use App\Models\CourseCompletion;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class KaryawanDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        return [
            [
                'label' => 'Course Diikuti',
                'value' => CourseEnrollment::where('user_id', $user->id)->count(),
            ],
            [
                'label' => 'Course Selesai',
                'value' => CourseCompletion::where('user_id', $user->id)->count(),
            ],
            [
                'label' => 'Jam Diklat',
                'value' => CourseCompletion::where('user_id', $user->id)->sum('earned_hours'),
            ],
        ];
    }

    public function getSummary(User $user): array
    {
        return [
            'enrollments' => CourseEnrollment::where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    public function getActivities(User $user): array
    {
        return [
            'courses' => CourseEnrollment::where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}
