<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Course\CourseInstructorService;

class EnsureInstructorCanManageCourse
{
    public function handle(Request $request, Closure $next)
{
    $course = $request->route('course');

    app(CourseInstructorService::class)
        ->authorizeModuleManagement($course, auth()->id());

    return $next($request);
}
}
