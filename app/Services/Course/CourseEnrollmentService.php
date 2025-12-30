<?php

namespace App\Services\Course;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CourseEnrollmentService
{
    public function enroll(string $enrollmentKey, User $user): Course
    {
        // 1. Cari course berdasarkan key
        $course = Course::where('enrollment_key', $enrollmentKey)
            ->where('status', 'published')
            ->first();

        if (!$course) {
            throw ValidationException::withMessages([
                'enrollment_key' => 'Enrollment key tidak valid atau course belum dipublish.',
            ]);
        }



        // 2. Cek permission via policy
        if (! $user->can('enroll', $course)) {
            abort(403);
        }

        // 3. Cek apakah sudah pernah enroll
        $alreadyEnrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyEnrolled) {
            throw ValidationException::withMessages([
                'enrollment_key' => 'Anda sudah terdaftar di course ini.',
            ]);
        }

        // 4. Simpan enrollment
        CourseEnrollment::create([
            'course_id' => $course->id,
            'user_id'   => $user->id,
            'status'    => 'enrolled',
        ]);

        return $course;
    }
}
