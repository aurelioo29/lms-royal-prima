<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseCompletion;
use App\Models\CourseEnrollment;
use App\Services\Course\CourseProgressService;
use App\Services\Course\CourseEnrollmentService;
use App\Http\Requests\CourseEnroll\EnrollCourseRequest;

class CourseEnrollmentController extends Controller
{
    // List semua course
    public function index(Request $request)
    {
        $courses = Course::query()
            ->where('status', 'published')

            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;

                $query->whereHas('torSubmission.planEvent', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })

            ->with([
                'type',
                'instructors' => function ($q) {
                    $q->wherePivot('status', 'active');
                },
            ])

            ->with(['torSubmission.planEvent', 'type'])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('course-enrollment.index', compact('courses'));
    }

    /**
     * Detail course:
     * - daftar modul
     * - progress course
     * - statistik
     */
    public function show(Course $course)
    {
        abort_unless(auth()->user()->can('access', $course), 403);

        $user = auth()->user();

        $course->load([
            'instructors' => function ($q) {
                $q->wherePivot('status', 'active');
            }
        ]);

        $modules = $course->modules()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'progresses' => fn($q) =>
                $q->where('user_id', $user->id),

                'quiz.attempts' => fn($q) =>
                $q->where('user_id', $user->id)
                    ->whereNotNull('submitted_at'),
            ])
            ->get();

        // ================= MODULES LOGIC =================
        $previousMandatoryQuizPassed = true;
        $previousRequiredModuleCompleted = true;

        // flow blokir modul berikutnya
        $flowBlocked = false;


        foreach ($modules as $module) {

            // ================= DEFAULT FLAGS =================
            $module->is_locked = false;
            $module->can_start_quiz = true;
            $module->quiz_passed = false;
            $module->quiz_attempts_exhausted = false;
            $module->quiz_waiting_review = false;


            $progressModule = $module->progresses->first();
            $quiz = $module->quiz;

            // ================= MODULE LOCK FLOW =================

            if ($flowBlocked) {
                $module->is_locked = true;
                $module->can_start_quiz = false;
                continue;
            }

            if ($module->is_required) {
                if (! $progressModule || $progressModule->status !== 'completed') {
                    $module->is_locked = true;
                    $module->can_start_quiz = false;

                    // 🔒 BLOK SEMUA SETELAHNYA
                    $flowBlocked = true;
                    continue;
                }
            }
            // ================= QUIZ LOGIC =================
            if ($quiz) {

                $attempts = $quiz->attempts;

                //quiz lulus = ADA attempt is_passed
                $module->quiz_passed = $attempts
                    ->contains(fn($a) => $a->status === 'reviewed_passed');

                //menunggu review 
                $module->quiz_waiting_review = $attempts
                    ->contains(fn($a) => $a->status === 'submitted');

                //attempt habis
                if (! is_null($quiz->max_attempts)) {
                    $usedAttempts = $attempts->count();
                    $module->quiz_attempts_exhausted =
                        $usedAttempts >= $quiz->max_attempts;
                }

                // atur akses quiz
                // modul WAJIB → harus selesai dulu
                if ($module->is_required) {
                    if (! $progressModule || $progressModule->status !== 'completed') {
                        $module->is_locked = true;
                        $module->can_start_quiz = false;

                        // 🔒 BLOK SEMUA SETELAHNYA
                        $flowBlocked = true;
                        continue;
                    }
                }

                //masih menunggu review → tidak boleh quiz lagi
                if ($module->quiz_waiting_review) {
                    $module->can_start_quiz = false;
                }

                //attempt habis → tidak bisa quiz
                if ($module->quiz_attempts_exhausted && ! $module->quiz_passed) {
                    $module->can_start_quiz = false;
                }


                // modul WAJIB → menentukan akses modul berikutnya
                if ($module->is_required) {
                    $previousRequiredModuleCompleted =
                        $progressModule && $progressModule->status === 'completed';
                }
                //quiz mandatory → menentukan modul berikutnya
                if ($quiz->is_mandatory && ! $module->quiz_passed) {
                    // $previousMandatoryQuizPassed = false;
                    $flowBlocked = true;
                }
            }
        }

        // ================= COURSE SUMMARY =================
        $progress = CourseProgressService::summary(
            $course,
            $user->id
        );

        return view(
            'course-enrollment.show',
            compact('course', 'modules', 'progress')
        );
    }





    // Form enroll course
    public function create(Course $course)
    {
        abort_unless(auth()->user()->can('enroll', $course), 403);

        return view('course-enrollment._form', compact('course'));
    }


    // Enroll course
    public function store(EnrollCourseRequest $request, CourseEnrollmentService $service)
    {
        $course = $service->enroll(
            $request->enrollment_key,
            auth()->user()
        );

        return redirect()
            ->route('employee.courses.show', $course)
            ->with('success', 'Berhasil mendaftar ke course.');
    }
}
