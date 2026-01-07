<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CanManageUsers;
use App\Http\Middleware\UpdateLastSeen;
use App\Http\Middleware\CheckCapability;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureInstructorCanManageCourse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', UpdateLastSeen::class);
        $middleware->alias([
            'can_manage_users' => CanManageUsers::class,
            'cap' => CheckCapability::class,
            'owns.course' => EnsureInstructorCanManageCourse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
