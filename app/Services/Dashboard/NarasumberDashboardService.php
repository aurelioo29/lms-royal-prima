<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\PlanEvent;
use App\Models\ModuleProgress;
use App\Models\CourseCompletion;
use App\Models\CourseEnrollment;
use App\Models\CourseInstructor;
use App\Models\InstructorDocument;
use App\Services\Dashboard\Contracts\DashboardRoleService;
use App\Services\Course\InstructorTeachingProgressService;

class NarasumberDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        $courses = CourseInstructor::with('course.modules')
            ->where('user_id', $user->id)
            ->whereIn('status', ['assigned', 'active'])
            ->get();

        $totalCourse = $courses->count();

        $totalHours = $courses->sum(
            fn($ci) => $ci->course?->training_hours ?? 0
        );

        $activeStudents = CourseEnrollment::whereIn(
            'course_id',
            $courses->pluck('course_id')
        )->count();

        return [
            'total_course'    => $totalCourse,
            'total_hours'     => $totalHours,
            'active_students' => $activeStudents,
            'account_status'  => $user->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function getSummary(User $user): array
    {
        $mot = InstructorDocument::where('user_id', $user->id)
            ->where('type', 'mot')
            ->latest()
            ->first();

        $upcomingSchedule = PlanEvent::whereHas('torSubmission.course.instructors', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->first();

        return [
            'mot' => $mot,
            'upcoming_schedule' => $upcomingSchedule,
            'assigned_courses' => CourseInstructor::with('course')
                ->where('user_id', $user->id)
                ->whereIn('status', ['assigned', 'active'])
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }


    public function getActivities(User $user): array
    {
        $courses = CourseInstructor::with('course.modules')
            ->where('user_id', $user->id)
            ->whereIn('status', ['assigned', 'active'])
            ->limit(5)
            ->get();

        $progress = $courses->map(function ($ci) {
            $course = $ci->course;

            $modules = $course->modules()
                ->where('is_required', true)
                ->where('is_active', true)
                ->pluck('id');

            $totalModules = $modules->count();

            if ($totalModules === 0) {
                $percent = 0;
            } else {
                $completed = ModuleProgress::whereIn('course_module_id', $modules)
                    ->where('status', 'completed')
                    ->distinct('user_id')
                    ->count();

                $enrolledUsers = CourseEnrollment::where('course_id', $course->id)->count();

                $percent = $enrolledUsers > 0
                    ? (int) floor(($completed / ($totalModules * $enrolledUsers)) * 100)
                    : 0;
            }

            return [
                'title' => $course->event_title,
                'status' => $ci->status,
                'progress_percent' => $percent,
            ];
        });

        return [
            'teaching_progress' => $progress,
        ];
    }
}
