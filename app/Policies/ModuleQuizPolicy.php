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
        return CourseEnrollment::where('course_id', $quiz->module->course_id)
            ->where('user_id', $user->id)
            ->exists();
    }
}
