<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CourseModule\StoreCourseModuleRequest;
use App\Http\Requests\CourseModule\UpdateCourseModuleRequest;

class CourseModuleController extends Controller
{
    // List all modules for a course
    public function index(Course $course)
    {
        $modules = $course->modules()
            ->orderBy('sort_order')
            ->get();

        return view('courses.modules.index', compact('course', 'modules'));
    }

    public function show(Course $course, CourseModule $module)
    {
        // Pastikan modul milik course yang sama
        abort_if($module->course_id !== $course->id, 404);

        // Pastikan modul aktif
        if (! $module->is_active) {
            abort(403, 'Modul tidak aktif');
        }

        $allModules = $course->modules()
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('courses.modules.preview', [
            'course' => $course,
            'module' => $module,
            'allModules' => $allModules
        ]);
    }

    // Show form to create a new module
    public function create(Course $course)
    {
        return view('courses.modules.create', compact('course'));
    }

    // Store a new module
    public function store(StoreCourseModuleRequest $request, Course $course)
    {
        $data = $request->validated();
        $data['course_id'] = $course->id;

        // handle upload file
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')
                ->store('course-modules', 'public');
        }

        // default sorting (auto append)
        if (empty($data['sort_order'])) {
            $maxOrder = CourseModule::where('course_id', $course->id)->max('sort_order') ?? 0;
            $data['sort_order'] = $maxOrder + 1;
        }

        CourseModule::create($data);

        return redirect()
            ->route('courses.modules.index', $data['course_id'])
            ->with('success', 'Modul berhasil ditambahkan');
    }

    // Show form to edit a module
    public function edit(Course $course, CourseModule $module)
    {
        abort_if($module->course_id !== $course->id, 404);
        return view('courses.modules.edit', compact('course', 'module'));
    }

    // Update a module
    public function update(UpdateCourseModuleRequest $request, Course $course, CourseModule $module)
    {
        abort_if($module->course_id !== $course->id, 404);
        $data = $request->validated();

        // handle upload baru
        if ($request->hasFile('file')) {
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }

            $data['file_path'] = $request->file('file')
                ->store('course-modules', 'public');
        }

        $module->update($data);

        return redirect()
            ->route('courses.modules.index', $module->course_id)
            ->with('success', 'Modul berhasil diperbarui');
    }

    // Delete a module
    public function destroy(Course $course, CourseModule $module)
    {
        abort_if($module->course_id !== $course->id, 404);

        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }

        $module->delete();

        return back()->with('success', 'Modul berhasil dihapus');
    }

    // Toggle active / inactive
    public function toggle(Course $course, CourseModule $module)
    {
        abort_if($module->course_id !== $course->id, 404);
        $module->update([
            'is_active' => ! $module->is_active
        ]);

        return back()->with('success', 'Status module diperbarui');
    }


    // Reorder module
    public function reorder(Request $request, Course $course, CourseModule $module)
    {
        abort_if($module->course_id !== $course->id, 404);
        $request->validate([
            'sort_order' => ['required', 'integer', 'min:1']
        ]);

        $module->update([
            'sort_order' => $request->sort_order
        ]);

        return back()->with('success', 'Urutan module diperbarui');
    }
}
