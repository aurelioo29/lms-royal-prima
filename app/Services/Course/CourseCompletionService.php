<?php

namespace App\Services\Course;


use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use App\Models\CourseCompletion;
use App\Models\CourseEnrollment;
use Illuminate\Support\Facades\DB;

class CourseCompletionService
{
    public function evaluate(int $courseId, int $userId): void
    {
        $requiredModules = CourseModule::where('course_id', $courseId)
            ->where('is_active', true)
            ->where('is_required', true)
            ->pluck('id');

        if ($requiredModules->isEmpty()) {
            return;
        }

        $completed = ModuleProgress::where('user_id', $userId)
            ->whereIn('course_module_id', $requiredModules)
            ->where('status', 'completed')
            ->count();

        if ($completed !== $requiredModules->count()) {
            return;
        }

        $this->completeCourse($courseId, $userId);
    }

    protected function completeCourse(int $courseId, int $userId): void
    {
        DB::transaction(function () use ($courseId, $userId) {

            // Guard: completion hanya 1x
            if (CourseCompletion::where('course_id', $courseId)
                ->where('user_id', $userId)
                ->exists()
            ) {
                return;
            }

            $course = Course::findOrFail($courseId);

            $enrollment = CourseEnrollment::where('course_id', $courseId)
                ->where('user_id', $userId)
                ->where('status', 'enrolled')
                ->first();

            if (! $enrollment) {
                return;
            }

            CourseCompletion::create([
                'course_id'     => $courseId,
                'user_id'       => $userId,
                'earned_hours'  => $course->training_hours,
                'completed_at'  => now(),
            ]);

            $enrollment->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        });
    }
}
