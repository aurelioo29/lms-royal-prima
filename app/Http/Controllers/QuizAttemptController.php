<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Services\Quiz\QuizAttemptService;
use App\Models\ModuleQuiz;
use App\Models\QuizAttempt;

class QuizAttemptController extends Controller
{
    public function __construct(
        protected QuizAttemptService $quizService
    ) {}

    // mulai quiz
    public function start(ModuleQuiz $quiz)
    {
        $this->authorize('start', $quiz);

        $attempt = $this->quizService
            ->startQuiz($quiz, auth()->user());

        return redirect()->route('quiz.attempt', $attempt->id);
    }

    // halaman pengerjaan quiz
    public function attempt(QuizAttempt $attempt)
    {
        $this->authorize('attempt', $attempt);

        $quiz = $attempt->quiz
            ->load(['questions.options']);

        return view('quiz.attempt', compact('attempt', 'quiz'));
    }

    // submit quiz
    public function submit(SubmitQuizRequest $request, QuizAttempt $attempt)
    {
        $this->authorize('submit', $attempt);

        $this->quizService->submitQuiz(
            $attempt,
            $request->validated()['answers']
        );

        return redirect()
            ->route('quiz.result', $attempt->id)
            ->with('success', 'Quiz berhasil dikumpulkan');
    }

    // hasil quiz
    public function result(QuizAttempt $attempt)
    {
        $this->authorize('viewResult', $attempt);

        $attempt->load([
            'quiz.questions',
            'answers.question',
            'answers.option',
        ]);

        return view('quiz.result', compact('attempt'));
    }
}
