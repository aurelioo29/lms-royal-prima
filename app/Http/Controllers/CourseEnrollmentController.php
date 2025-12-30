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
                $q->where('user_id', auth()->id())
            ])
            ->get();

        // COURSE SUMMARY
        $progress = CourseProgressService::summary(
            $course,
            auth()->id()
        );

        return view(
            'course-enrollment.show',
            compact('course', 'modules', 'progress')
        );
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


    public function complete(Course $course)
    {
        $user = auth()->user();

        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Update enrollment
        $enrollment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Insert ke course_completions
        CourseCompletion::firstOrCreate(
            [
                'course_id' => $course->id,
                'user_id'   => $user->id,
            ],
            [
                'earned_hours' => $course->training_hours,
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'Course berhasil diselesaikan.');
    }
}
