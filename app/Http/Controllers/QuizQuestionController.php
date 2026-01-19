<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\CourseModule;
use App\Models\QuizQuestion;
use App\Services\Quiz\QuizQuestionService;
use App\Http\Requests\QuizQuestion\StoreQuizQuestionRequest;
use App\Http\Requests\QuizQuestion\UpdateQuizQuestionRequest;


class QuizQuestionController extends Controller
{

    public function __construct(
        protected QuizQuestionService $service
    ) {}

    private function guardQuiz(Course $course, CourseModule $module): void
    {
        abort_if($module->course_id !== $course->id, 404);
        abort_if(!$module->quiz, 404);
    }

    // determine route prefix based on current route
    private function routePrefix(): string
    {
        return request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';
    }


    public function index($courseId, $moduleId)
    {
        $course = Course::findOrFail($courseId);

        $module = CourseModule::with(['quiz.questions.options'])
            ->where('id', $moduleId)
            ->where('course_id', $course->id)
            ->firstOrFail();

        abort_if(!$module->quiz, 404, 'Quiz belum dibuat');

        return view('quiz-questions.index', [
            'course'      => $course,
            'module'      => $module,
            'quiz'        => $module->quiz,
            'questions'   => $module->quiz->questions->sortBy('sort_order'),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function create(Course $course, CourseModule $module)
    {
        $this->guardQuiz($course, $module);

        return view('quiz-questions.create', [
            'course'      => $course,
            'module'      => $module,
            'quiz'        => $module->quiz,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function store(StoreQuizQuestionRequest $request, Course $course,  CourseModule $module)
    {
        $this->guardQuiz($course, $module);

        $this->service->create(
            $module->quiz,
            $request->validated()
        );

        return redirect()
            ->route($this->routePrefix() . '.modules.quiz.questions.index', [
                $course->id,
                $module->id,
            ])
            ->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit(Course $course, CourseModule $module, QuizQuestion $question)
    {
        $this->guardQuiz($course, $module);
        abort_if($question->module_quiz_id !== $module->quiz->id, 404);

        $quiz = $module->quiz()->with('questions.options')->firstOrFail();
        // $question->load('options');

        return view('quiz-questions.edit', [
            'course'      => $course,
            'module'      => $module,
            'quiz'        => $quiz,
            // 'question'    => $question,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function bulkUpdate(StoreQuizQuestionRequest $request, Course $course, CourseModule $module)
    {
        $this->guardQuiz($course, $module);

        $this->service->sync(
            $module->quiz,
            $request->validated()
        );

        return redirect()
            ->route($this->routePrefix() . '.modules.quiz.questions.index', [
                $course->id,
                $module->id,
            ])
            ->with('success', 'Soal berhasil diperbarui');
    }


    public function update(UpdateQuizQuestionRequest $request, Course $course, CourseModule $module, QuizQuestion $question)
    {
        $this->guardQuiz($course, $module);
        abort_if($question->module_quiz_id !== $module->quiz->id, 404);

        $this->service->update(
            $question,
            $request->validated()
        );

        return back()->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy(Course $course, CourseModule $module, QuizQuestion $question)
    {
        $this->guardQuiz($course, $module);
        abort_if($question->module_quiz_id !== $module->quiz->id, 404);

        $this->service->delete($question);

        return back()->with('success', 'Soal berhasil dihapus');
    }
}
