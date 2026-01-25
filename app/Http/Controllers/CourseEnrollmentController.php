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
            'instructors' => fn($q) => $q->wherePivot('status', 'active'),
        ]);

        $modules = $course->modules()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'progresses' => fn($q) => $q->where('user_id', $user->id),
                'quiz.attempts' => fn($q) => $q
                    ->where('user_id', $user->id)
                    ->whereNotNull('submitted_at'),
            ])
            ->get();

        $flowBlocked = false;

        foreach ($modules as $module) {

            // DEFAULT FLAGS (WAJIB ADA)
            $module->is_locked = false;
            $module->can_start_quiz = false;
            $module->quiz_passed = false;
            $module->quiz_waiting_review = false;
            $module->quiz_attempts_exhausted = false;

            $progress = $module->progresses->first();
            $isCompleted = $progress && $progress->status === 'completed';

            $quiz = $module->quiz;
            $attempts = $quiz?->attempts ?? collect();

            //HITUNG STATUS QUIZ
            if ($quiz) {
                $module->quiz_passed = $attempts->contains(
                    fn($a) => $a->status === 'reviewed_passed'
                );

                $module->quiz_waiting_review = $attempts->contains(
                    fn($a) => $a->status === 'submitted'
                );

                if (!is_null($quiz->max_attempts)) {
                    $module->quiz_attempts_exhausted =
                        $attempts->count() >= $quiz->max_attempts;
                }
            }


            // FLOW GLOBAL SUDAH TERBLOKIR
            if ($flowBlocked) {
                $module->is_locked = true;
                continue;
            }

            // BOLEH MULAI QUIZ?
            if ($quiz && $isCompleted) {
                if (
                    !$module->quiz_passed &&
                    !$module->quiz_waiting_review &&
                    !$module->quiz_attempts_exhausted
                ) {
                    $module->can_start_quiz = true;
                }
            }

            // MODUL WAJIB → CEK SYARAT FLOW
            if ($module->is_required) {
                $quizRequirementMet =
                    ($quiz && $quiz->is_mandatory)
                    ? $module->quiz_passed
                    : true;

                if (!$isCompleted || !$quizRequirementMet) {
                    $flowBlocked = true;
                }
            }

            //  MENUNGGU REVIEW → BLOKIR FLOW BERIKUTNYA
            if ($module->quiz_waiting_review) {
                $flowBlocked = true;
            }
        }

        $progress = CourseProgressService::summary($course, $user->id);

        return view('course-enrollment.show', compact(
            'course',
            'modules',
            'progress'
        ));
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
