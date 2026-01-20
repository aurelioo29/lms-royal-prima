<?php

namespace App\Policies;

use App\Models\ModuleQuiz;
use App\Models\User;
use App\Models\CourseEnrollment;

class ModuleQuizPolicy
{
    public function start(User $user, ModuleQuiz $quiz): bool
    {
        // user harus enroll course
        $enrolled = CourseEnrollment::where('course_id', $quiz->module->course_id)
            ->where('user_id', $user->id)
            ->exists();

        if (! $enrolled) {
            return false;
        }

        // quiz hanya boleh dikerjakan sekali
        return ! $quiz->attempts()
            ->where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->exists();
    }
}
