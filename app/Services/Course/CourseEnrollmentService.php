<?php

namespace App\Services\Course;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\CourseModule;
use App\Models\ModuleProgress;
use DomainException;

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
            'enrolled_at'    => now(),
        ]);

        return $course;
    }

    // Dapatkan daftar enrollment untuk sebuah course
    public function getEnrollments(Course $course)
    {
        return CourseEnrollment::query()
            ->with('user')
            ->where('course_id', $course->id)
            ->orderByDesc('enrolled_at')
            ->get();
    }

    // Keluarkan peserta dari course

    public function removeParticipant(CourseEnrollment $enrollment): void
    {
        if ($enrollment->status === 'completed') {
            throw new DomainException(
                'Peserta yang sudah menyelesaikan course tidak dapat dikeluarkan.'
            );
        }

        DB::transaction(function () use ($enrollment) {

            // hapus progress module
            ModuleProgress::where('user_id', $enrollment->user_id)
                ->whereIn(
                    'course_module_id',
                    CourseModule::where('course_id', $enrollment->course_id)
                        ->pluck('id')
                )
                ->delete();

            // HAPUS ENROLLMENT (INI INTINYA)
            $enrollment->delete();
        });
    }
}
