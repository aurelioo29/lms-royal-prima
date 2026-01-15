<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Services\Quiz\QuizService;
use App\Models\ModuleQuiz;
use App\Models\QuizAttempt;

class QuizAttemptController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function start(ModuleQuiz $quiz)
    {
        $this->authorize('start', $quiz);

        $attempt = $this->quizService
            ->startQuiz($quiz, auth()->user());

        return redirect()->route('quiz.attempt', $attempt);
    }

    public function attempt(QuizAttempt $attempt)
    {
        $this->authorize('attempt', $attempt);

        $quiz = $attempt->quiz->load('questions.options');

        return view('quiz.attempt', compact('attempt', 'quiz'));
    }

    public function submit(SubmitQuizRequest $request, QuizAttempt $attempt)
    {
        $attempt = $this->quizService->submitQuiz(
            $attempt,
            $request->validated()['answers']
        );

        return redirect()
            ->route('quiz.result', $attempt)
            ->with('success', 'Quiz berhasil dikumpulkan');
    }

    public function result(QuizAttempt $attempt)
    {
        $this->authorize('viewResult', $attempt);

        return view('quiz.result', compact('attempt'));
    }
}
