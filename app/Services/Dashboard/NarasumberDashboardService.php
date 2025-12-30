<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\PlanEvent;
use App\Models\CourseCompletion;
use App\Models\InstructorDocument;
use App\Models\CourseInstructor;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class NarasumberDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        $courses = CourseInstructor::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('course.enrollments')
            ->get();

        // Total course yang diajar
        $totalCourse = $courses->count();

        // Total peserta aktif
        $activeStudents = $courses->sum(
            fn($ci) => $ci->course?->enrollments?->count() ?? 0
        );

        return [
            'total_course'    => $totalCourse,
            'total_hours'     => null, // (akan kita isi nanti jika ada tracking jam)
            'active_students' => $activeStudents,
            'account_status'  => $user->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function getSummary(User $user): array
    {

        $mot = InstructorDocument::where('user_id', $user->id)->latest()->first();

        return [

            'mot' => $mot,
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
        return [
            'teaching_progress' => CourseInstructor::with('course')
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get(),
        ];
    }
}
