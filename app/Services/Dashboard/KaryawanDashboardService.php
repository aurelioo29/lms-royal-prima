<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\ModuleProgress;
use App\Models\CourseCompletion;
use App\Models\CourseEnrollment;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class KaryawanDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        return [
            'course_aktif' => CourseEnrollment::where('user_id', $user->id)->count(),

            'course_selesai' => CourseCompletion::where('user_id', $user->id)->count(),

            'total_jam' => CourseCompletion::where('user_id', $user->id)
                ->sum('earned_hours'),
        ];
    }

    public function getSummary(User $user): array
    {
        $enrollments = CourseEnrollment::with('course.modules')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $progress = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;

            $totalModules = $course->modules()
                ->where('is_required', true)
                ->where('is_active', true)
                ->count();

            if ($totalModules === 0) {
                $percent = 0;
            } else {
                $completedModules = ModuleProgress::where('user_id', $user->id)
                    ->whereIn(
                        'course_module_id',
                        $course->modules->pluck('id')
                    )
                    ->where('status', 'completed')
                    ->count();

                $percent = (int) floor(($completedModules / $totalModules) * 100);
            }

            return [
                'title'   => $course->event_title,
                'percent' => $percent,
            ];
        });

        $certificates = CourseCompletion::with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn($c) => [
                'title' => $c->course->event_title,
            ]);

        return [
            'progress'     => $progress,
            'certificates' => $certificates,
        ];
    }

    public function getActivities(User $user): array
    {
        $timeline = CourseEnrollment::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($enrollment) use ($user) {
                $course = $enrollment->course;

                $totalModules = $course->modules()
                    ->where('is_required', true)
                    ->where('is_active', true)
                    ->count();

                $completedModules = ModuleProgress::where('user_id', $user->id)
                    ->whereIn('course_module_id', $course->modules->pluck('id'))
                    ->where('status', 'completed')
                    ->count();

                $percent = $totalModules > 0
                    ? (int) floor(($completedModules / $totalModules) * 100)
                    : 0;

                return [
                    'title' => $course->event_title,
                    'meta'  => "Progress {$percent}%",
                    'time'  => $enrollment->updated_at->diffForHumans(),
                ];
            });

        return [
            'timeline' => $timeline,
        ];
    }
}
