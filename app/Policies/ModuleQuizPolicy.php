<?php

namespace App\Policies;

use App\Models\ModuleQuiz;
use App\Models\User;
use App\Models\CourseEnrollment;
use App\Models\QuizAttempt;

class ModuleQuizPolicy
{
    public function start(User $user, ModuleQuiz $quiz): bool
    {
        // 1️⃣ user harus enroll
        $enrolled = CourseEnrollment::where('course_id', $quiz->module->course_id)
            ->where('user_id', $user->id)
            ->exists();

        if (! $enrolled) {
            return false;
        }

        // 2️⃣ jika sudah lulus → tidak boleh start lagi
        $passed = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('is_passed', true)
            ->exists();

        if ($passed) {
            return false;
        }

        // 3️⃣ unlimited attempt
        if (is_null($quiz->max_attempts)) {
            return true;
        }

        // 4️⃣ cek sisa attempt
        $usedAttempts = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->count();

        return $usedAttempts < $quiz->max_attempts;
    }
}
