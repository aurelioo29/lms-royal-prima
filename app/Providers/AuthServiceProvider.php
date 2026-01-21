<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\ModuleQuiz;
use App\Models\QuizAttempt;
use App\Policies\CoursePolicy;
use App\Policies\ModuleQuizPolicy;
use App\Policies\QuizAttemptPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class      => CoursePolicy::class,
        ModuleQuiz::class => ModuleQuizPolicy::class,
        QuizAttempt::class => QuizAttemptPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
