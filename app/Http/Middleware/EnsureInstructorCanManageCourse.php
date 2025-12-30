<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInstructorCanManageCourse
{
    public function handle(Request $request, Closure $next)
    {
        $course = $request->route('course');
        $userId = auth()->id();

        // pastikan course ada
        abort_if(!$course, 404);

        $isInstructor = $course->instructors()
            ->where('user_id', $userId)
            ->where('can_manage_modules', true)
            ->exists();

        abort_unless($isInstructor, 403);

        return $next($request);
    }
}
