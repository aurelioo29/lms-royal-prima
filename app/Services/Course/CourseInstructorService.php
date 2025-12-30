<?php

namespace App\Services\Course;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use Illuminate\Support\Carbon;

class CourseInstructorService
{

    public function canManageModules(Course $course, int $userId): bool
    {
        if (auth()->user()->canCreateCourses()) {
            return true;
        }

        return $course->instructors()
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where('can_manage_modules', true)
            ->exists();
    }

    public function getActiveInstructors(Course $course)
    {
        return $course->instructors()
            ->wherePivot('status', 'active')
            // ->with('user')
            ->get();
    }


    public function authorizeModuleManagement(Course $course, int $userId): void
    {
        abort_unless(
            $this->canManageModules($course, $userId),
            403
        );
    }
}
