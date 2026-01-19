<?php

namespace App\Policies;

use App\Models\QuizAttempt;
use App\Models\ModuleQuiz;
use App\Models\User;

class QuizAttemptPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function start(User $user, ModuleQuiz $quiz): bool
    {
        // contoh rule: user harus terdaftar di course
        return $quiz->module
            ->course
            ->users()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function attempt(User $user, QuizAttempt $attempt): bool
    {
        return $attempt->user_id === $user->id
            && is_null($attempt->submitted_at);
    }

    public function submit(User $user, QuizAttempt $attempt): bool
    {
        return $attempt->user_id === $user->id
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
