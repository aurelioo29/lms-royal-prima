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

    private function finalizeAttempt(QuizAttempt $attempt): void
    {

        if ($attempt->status !== 'submitted') {
            return;
        }
        $essayAnswers = $attempt->answers()
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->with('review')
            ->get();

        // jika ADA essay tapi BELUM DIREVIEW → STOP
        if (
            $essayAnswers->isNotEmpty() &&
            $essayAnswers->contains(fn($a) => $a->review === null)
        ) {
            return;
        }

        // SATU-SATUNYA TEMPAT SET STATUS
        $attempt->recalculateScore();
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

        // OPTIONAL: cache score di answer (BUKAN sumber kebenaran)
        $answer->update([
            'is_correct' => $request->boolean('is_correct'),
            'score' => $request->boolean('is_correct')
                ? $answer->question->score
                : 0,
        ]);

        $this->finalizeAttempt($answer->attempt);

        return back()->with('success', 'Jawaban essay berhasil dinilai.');
    }
}
