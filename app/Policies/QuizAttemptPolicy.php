<?php

namespace App\Policies;

use App\Models\QuizAttempt;
use App\Models\ModuleQuiz;
use App\Models\User;
use App\Models\CourseEnrollment;

class QuizAttemptPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function attempt(User $user, QuizAttempt $attempt): bool
    {
        return $attempt->user_id === $user->id
            && $attempt->status === 'started'
            && is_null($attempt->submitted_at);
    }

    public function submit(User $user, QuizAttempt $attempt): bool
    {
        return $attempt->user_id === $user->id
            && $attempt->status === 'started'
            && is_null($attempt->submitted_at);
    }


    public function viewResult(User $user, QuizAttempt $attempt): bool
    {
        // pemilik attempt
        if ($attempt->user_id === $user->id) {
            return true;
        }

        // role tertentu (opsional)
        return $user->hasAnyRole([
            'Direktur',
            'Kabid Diklat',
            'Admin',
        ]);
    }
}
