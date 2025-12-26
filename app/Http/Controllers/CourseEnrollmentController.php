<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CourseEnrollmentService;
use App\Services\CourseProgressService;
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
    public function store(
        EnrollCourseRequest $request,
        CourseEnrollmentService $service
    ) {
        $course = $service->enroll(
            $request->enrollment_key,
            auth()->user()
        );

        return redirect()
            ->route('employee.courses.show', $course)
            ->with('success', 'Berhasil mendaftar ke course.');
    }
}
