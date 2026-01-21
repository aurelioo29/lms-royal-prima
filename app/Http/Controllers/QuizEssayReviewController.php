<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\QuizAnswerReview;
use App\Models\QuizAttempt;
use App\Models\Course;
use App\Models\CourseModule;

use Illuminate\Http\Request;

class QuizEssayReviewController extends Controller
{
    private function moduleRoutePrefix(): string
    {
        return request()->routeIs('instructor.*')
            ? 'instructor.courses'
            : 'courses';
    }

    public function index(Course $course, CourseModule $module)
    {
        $routePrefix = $this->moduleRoutePrefix();

        $attempts = QuizAttempt::with(['user'])
            ->whereHas('moduleQuiz', function ($q) use ($module) {
                $q->where('course_module_id', $module->id);
            })
            ->whereHas('answers.question', function ($q) {
                $q->where('type', 'essay');
            })
            ->latest()
            ->get();

        return view('courses.modules.review-quiz-essay.index', compact(
            'course',
            'module',
            'attempts',
            'routePrefix'

        ));
    }

    // Detail jawaban essay per attempt
    public function show(
        Course $course,
        CourseModule $module,
        QuizAttempt $attempt
    ) {
        // abort_unless(
        //     $attempt->module_id === $module->id,
        //     404
        // );
        $routePrefix = $this->moduleRoutePrefix();

        $attempt->load([
            'user',
            'answers' => function ($q) {
                $q->whereHas('question', function ($q) {
                    $q->where('type', 'essay');
                });
            },
            'answers.question',
            'answers.review'
        ]);


        return view('courses.modules.review-quiz-essay.show', compact(
            'course',
            'module',
            'attempt',
            'routePrefix'
        ));
    }

    public function store(
        Course $course,
        CourseModule $module,
        QuizAnswer $answer,
        Request $request
    ) {
        abort_unless($answer->question?->type === 'essay', 404);

        $request->validate([
            'is_correct' => ['required', 'boolean'],
            'note'       => ['nullable', 'string'],
        ]);

        QuizAnswerReview::updateOrCreate(
            ['quiz_answer_id' => $answer->id],
            [
                'reviewer_id' => auth()->id(),
                'is_correct'  => $request->boolean('is_correct'),
                'note'        => $request->note,
            ]
        );

        // update nilai answer
        $answer->update([
            'is_correct' => $request->boolean('is_correct'),
            'score' => $request->boolean('is_correct')
                ? $answer->question->score
                : 0,
        ]);

        $answer->attempt->recalculateScore();

        return back()->with('success', 'Jawaban essay berhasil dinilai.');
    }
}
