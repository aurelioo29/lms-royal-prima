<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\PlanEvent;
use App\Models\CourseCompletion;
use App\Models\InstructorDocument;
use App\Models\PlanEventInstructor;
use App\Services\Dashboard\Contracts\DashboardRoleService;

class NarasumberDashboardService implements DashboardRoleService
{
    public function getStats(User $user): array
    {
        // Query dasar (JANGAN di-execute dulu)
        $instructorSessions = PlanEventInstructor::where('user_id', $user->id)
            ->where('status', 'completed');

        // Total sesi mengajar
        // $totalSessions = $instructorSessions->count();

        // Total event unik (course diajar)
        $totalCourse = (clone $instructorSessions)
            ->distinct('plan_event_id')
            ->count('plan_event_id');

        // Total jam mengajar
        $totalHours = (clone $instructorSessions)
            ->sum('teaching_hours');

        // Total peserta (VALID via relasi TOR → Course → Enrollment)
        $activeStudents = PlanEventInstructor::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('planEvent.torSubmission.course.enrollments')
            ->with('planEvent.torSubmission.course.enrollments')
            ->get()
            ->sum(
                fn($i) =>
                $i->planEvent
                    ->torSubmission
                    ?->course
                    ?->enrollments
                    ?->count() ?? 0
            );

        return [
            'total_course'    => $totalCourse,
            'total_hours'     => $totalHours,
            'active_students' => $activeStudents,
            'account_status'  => $user->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }

    public function getSummary(User $user): array
    {

        $mot = InstructorDocument::where('user_id', $user->id)->latest()->first();

        return [

            'mot' => $mot,
            'upcoming_schedule' => PlanEventInstructor::with('planEvent')
                ->where('user_id', $user->id)
                ->whereIn('status', ['assigned', 'confirmed'])
                ->whereHas(
                    'planEvent',
                    fn($q) =>
                    $q->whereDate('start_date', '>=', now())
                )
                ->orderBy(
                    PlanEvent::select('start_date')
                        ->whereColumn('plan_events.id', 'plan_event_instructors.plan_event_id')
                )
                ->first(),
        ];
    }

    public function getActivities(User $user): array
    {
        return [
            'teaching_progress' => PlanEventInstructor::with('planEvent')
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get(),
        ];
    }
}
