<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Services\Course\CourseInstructorService;
use App\Http\Requests\CourseModule\StoreCourseModuleRequest;
use App\Http\Requests\CourseModule\UpdateCourseModuleRequest;
use App\Services\Course\CourseEnrollmentService;
use App\Models\CourseEnrollment;
use DomainException;

class CourseModuleController extends Controller
{
    private function moduleRoutePrefix(): string
    {
        return request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';
    }


    // List all modules for a course
    public function index(Course $course, CourseInstructorService $service)
    {

        $service->authorizeModuleManagement($course, auth()->id());


        $modules = $course->modules()
            ->with([
                'quiz' => function ($q) {
                    $q->withCount([
                        'questions',
                        'questions as essay_questions_count' => function ($q2) {
                            $q2->where('type', 'essay');
                        },
                    ]);
                },
                'quiz.attempts' => function ($q) {
                    $q->where('user_id', auth()->id())
                        ->orderByDesc('created_at');
                },
            ])
            ->orderBy('sort_order', 'asc')
            ->get();

        $enrolledCount = $course->enrollments()
            ->active()
            ->count();


        $mentors = $service->getActiveInstructors($course);

        // menentukan route prefix sesuai dengan route yang diakses
        $routePrefix = request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';

        return view('courses.modules.index', compact('course', 'modules', 'mentors', 'enrolledCount', 'routePrefix'));
    }

    public function show(Course $course, CourseModule $module)
    {
        // Pastikan modul milik course yang sama
        abort_if($module->course_id !== $course->id, 404);

        // hanya user yang enroll / instructor / admin
        if (
            !auth()->user()->canCreateCourses() &&
            !$course->enrollments()->where('user_id', auth()->id())->exists() &&
            !$course->instructors()->where('user_id', auth()->id())->exists()
        ) {
            abort(403);
        }

        $allModules = $course->modules()

            ->orderBy('sort_order', 'asc')
            ->get();

        // menentukan route prefix sesuai dengan route yang diakses
        $routePrefix = request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';

        return view('courses.modules.preview', [
            'course' => $course,
            'module' => $module,
            'allModules' => $allModules,
            'routePrefix' => $routePrefix,

        ]);
    }

    // Show form to create a new module
    public function create(Course $course, CourseInstructorService $service)
    {
        $service->authorizeModuleManagement($course, auth()->id());

        // menentukan route prefix sesuai dengan route yang diakses
        $routePrefix = request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';

        return view('courses.modules.create', compact('course', 'routePrefix'));
    }

    // Store a new module
    public function store(StoreCourseModuleRequest $request, Course $course, CourseInstructorService $service)
    {
        $service->authorizeModuleManagement($course, auth()->id());

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

        $module = CourseModule::create($data);

        // Cek apakah quiz harus dibuat
        $shouldCreateQuiz =
            $request->boolean('has_quiz') ||
            $data['type'] === 'quiz';

        //JIKA MODUL BERTIPE QUIZ
        if ($shouldCreateQuiz && !empty($data['quiz'])) {
            // normalize max_attempts
            if (empty($data['quiz']['max_attempts'])) {
                $data['quiz']['max_attempts'] = null;
            }

            $module->quiz()->create($data['quiz']);
        }

        return redirect()
            ->route($this->moduleRoutePrefix() . '.modules.index', $data['course_id'])
            ->with('success', 'Modul berhasil ditambahkan');
    }

    // Show form to edit a module
    public function edit(Course $course, CourseModule $module, CourseInstructorService $service)
    {
        abort_if($module->course_id !== $course->id, 404);
        $service->authorizeModuleManagement($course, auth()->id());

        $module->load('quiz');

        // menentukan route prefix sesuai dengan route yang diakses
        $routePrefix = request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';

        return view('courses.modules.edit', compact('course', 'module', 'routePrefix'));
    }

    // Update a module
    public function update(UpdateCourseModuleRequest $request, Course $course, CourseModule $module, CourseInstructorService $service)
    {
        abort_if($module->course_id !== $course->id, 404);
        $service->authorizeModuleManagement($course, auth()->id());
        $data = $request->validated();

        // Pisahkan quiz dari data module
        $quizData = $data['quiz'] ?? null;
        unset($data['quiz']);

        // handle upload baru
        if ($request->hasFile('file')) {
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }

            $data['file_path'] = $request->file('file')
                ->store('course-modules', 'public');
        }

        $module->update($data);

        // Tentukan apakah quiz harus ada
        $shouldHaveQuiz =
            $request->boolean('has_quiz') ||
            $data['type'] === 'quiz';

        // 🚨 Modul tipe quiz WAJIB punya quiz
        if ($data['type'] === 'quiz' && empty($quizData)) {
            throw ValidationException::withMessages([
                'quiz' => 'Data quiz wajib diisi untuk modul bertipe quiz.'
            ]);
        }

        // Handle quiz
        if ($shouldHaveQuiz && $quizData) {
            // normalize max_attempts
            if (empty($quizData['max_attempts'])) {
                $quizData['max_attempts'] = null;
            }

            // update or create
            $module->quiz()
                ->updateOrCreate(
                    ['course_module_id' => $module->id],
                    $quizData
                );
        } else {
            // hapus quiz jika user uncheck
            $module->quiz()?->delete();
        }

        return redirect()
            ->route($this->moduleRoutePrefix() . '.modules.index', $module->course_id)
            ->with('success', 'Modul berhasil diperbarui');
    }

    // Delete a module
    public function destroy(Course $course, CourseModule $module, CourseInstructorService $service)
    {
        abort_if($module->course_id !== $course->id, 404);
        $service->authorizeModuleManagement($course, auth()->id());

        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }

        // hapus quiz (jika ada)    
        if ($module->quiz) {
            $module->quiz()->delete();
        }

        $module->delete();

        return back()->with('success', 'Modul berhasil dihapus');
    }

    // Toggle active / inactive
    public function toggle(Course $course, CourseModule $module, CourseInstructorService $service)
    {
        abort_if($module->course_id !== $course->id, 404);
        $service->authorizeModuleManagement($course, auth()->id());

        // Cek jika mengaktifkan module quiz, pastikan quiz memiliki soal
        if (! $module->is_active && $module->quiz) {
            if (! $module->quiz || $module->quiz->questions()->count() === 0) {
                return back()->withErrors([
                    'module' => 'Quiz belum memiliki soal.'
                ]);
            }
        }

        $module->update([
            'is_active' => ! $module->is_active
        ]);

        return back()->with('success', 'Status module diperbarui');
    }


    // Reorder module
    public function reorder(Request $request, Course $course, CourseModule $module, CourseInstructorService $service)
    {
        abort_if($module->course_id !== $course->id, 404);
        $service->authorizeModuleManagement($course, auth()->id());
        $request->validate([
            'sort_order' => ['required', 'integer', 'min:1']
        ]);

        $module->update([
            'sort_order' => $request->sort_order
        ]);

        return back()->with('success', 'Urutan module diperbarui');
    }

    // List enrollments for a course
    public function enrollments(
        Course $course,
        CourseInstructorService $service,
        CourseEnrollmentService $enrollmentService
    ) {
        $service->authorizeModuleManagement($course, auth()->id());

        $enrollments = $enrollmentService->getEnrollments($course);

        return view('courses.modules.enrollment.index', compact(
            'course',
            'enrollments',
        ));
    }


    public function destroyUser(
        Course $course,
        CourseEnrollment $enrollment,
        CourseEnrollmentService $service
    ) {
        abort_unless($enrollment->course_id === $course->id, 404);

        try {
            $service->removeParticipant($enrollment);
        } catch (DomainException $e) {
            return back()->withErrors([
                'enrollment' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Peserta berhasil dikeluarkan dari course.');
    }
}
