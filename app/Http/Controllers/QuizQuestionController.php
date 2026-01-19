<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Services\Quiz\QuizQuestionService;
use App\Http\Requests\QuizQuestion\StoreQuizQuestionRequest;
use App\Http\Requests\QuizQuestion\UpdateQuizQuestionRequest;

class QuizQuestionController extends Controller
{
    public function __construct(
        protected QuizQuestionService $service
    ) {}


    public function index(Course $course, CourseModule $module)
    {
        // Pastikan module milik course ini
        abort_if($module->course_id !== $course->id, 404);

        // Ambil semua pertanyaan beserta opsi, urutkan berdasarkan sort_order
        $questions = $module->quiz
            ->questions()
            ->with(['options' => function ($q) {
                $q->orderBy('sort_order'); // urutkan opsi juga
            }])
            ->orderBy('sort_order') // urutkan soal
            ->get();

        // Tentukan route prefix agar routing bisa dinamis (admin / instructor)
        $routePrefix = request()->routeIs('instructor.*') ? 'instructor.courses' : 'courses';

        return view('quiz-questions.index', compact(
            'course',
            'module',
            'questions',
            'routePrefix'
        ));
    }

    public function create(Course $course, CourseModule $module)
    {
        abort_if($module->type !== 'quiz', 404);

        return view('quiz-questions.create', compact(
            'course',
            'module'
        ));
    }

    public function store(StoreQuizQuestionRequest $request, Course $course, CourseModule $module)
    {
        $this->service->create(
            $module->quiz,
            $request->validated()
        );

        return redirect()
            ->route('courses.modules.quiz.questions.index', [$course, $module])
            ->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit(Course $course, CourseModule $module, QuizQuestion $question)
    {
        return view('quiz-questions.edit', compact(
            'course',
            'module',
            'question'
        ));
    }


    public function update(UpdateQuizQuestionRequest $request, Course $course, CourseModule $module, QuizQuestion $question)
    {
        $this->service->update(
            $question,
            $request->validated()
        );

        return back()->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy(Course $course, CourseModule $module, QuizQuestion $question)
    {
        $this->service->delete($question);

        return back()->with('success', 'Soal berhasil dihapus');
    }
}
