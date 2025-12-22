<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class CoursePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Apakah user boleh enroll course
     */
    public function enroll(User $user, Course $course): bool
    {
        return $user->role?->name === 'Karyawan' && $course->status === 'published';
    }

    /**
     * Apakah user boleh mengakses course (modul)
     */
    public function access(User $user, Course $course): bool
    {
        return $course->enrollments()
            ->where('user_id', $user->id)
            ->exists();
    }
}
