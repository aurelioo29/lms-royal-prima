<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CourseEnrollmentService;
use App\Http\Requests\CourseEnroll\EnrollCourseRequest;

class CourseEnrollmentController extends Controller
{

    public function index(Request $request)
    {
        $courses = Course::query()
            ->where('status', 'published')

            //Search
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;

                $query->whereHas('torSubmission.planEvent', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })

            ->with([
                'torSubmission.planEvent',
                'type'
            ])

            //Urutan terbaru
            ->latest()

            //Pagination + query string
            ->paginate(9)
            ->withQueryString();
        return view('course-enrollment.index', compact('courses'));
    }

    public function show(Course $course)
    {
        if (auth()->user()->can('access', $course)) {
            // Sudah enrolled, tampil content course
            return view('course-enrollment.show', compact('course'));
        }
        // Belum enrolled, tampil form enroll
        return view('course-enrollment._form', compact('course'));
    }

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
