<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Services\CourseProgressService;

class EmployeeCourseModuleController extends Controller
{
    // (Optional) List modul jika dibuka dari halaman terpisah
    public function index(Course $course)
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

        return view(
            'course-enrollment.module-employee.index',
            compact('course', 'modules')
        );
    }

    // Buka / preview modul
    public function show(Course $course, CourseModule $module)
    {
        abort_unless(auth()->user()->can('access', $course), 403);
        abort_if($module->course_id !== $course->id, 404);
        abort_if(! $module->is_active, 403);

        if (CourseProgressService::isLocked(
            $course,
            $module,
            auth()->id()
        )) {
            return redirect()
                ->route('employee.courses.show', $course)
                ->with('error', 'Selesaikan modul sebelumnya terlebih dahulu.');
        }

        $progress = CourseProgressService::markInProgress(
            $module,
            auth()->id()
        );

        return view(
            'course-enrollment.module-employee.index',
            compact('course', 'module', 'progress')
        );
    }

    // Tandai modul selesai
    public function complete(Course $course, CourseModule $module)
    {
        abort_unless(auth()->user()->can('access', $course), 403);
        abort_if($module->course_id !== $course->id, 404);

        CourseProgressService::markCompleted(
            $module,
            auth()->id()
        );

        return redirect()
            ->route('employee.courses.show', $course)
            ->with('success', 'Modul berhasil diselesaikan.');
    }
}
