<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\QuizAttempt;
use App\Policies\CoursePolicy;
use App\Policies\QuizAttemptPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class      => CoursePolicy::class,
        QuizAttempt::class => QuizAttemptPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
