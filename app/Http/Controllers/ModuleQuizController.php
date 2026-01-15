<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Models\CourseModule;
use App\Services\Quiz\QuizService;
use App\Models\ModuleQuiz;

class ModuleQuizController extends Controller
{
    // dependency injection
    public function __construct(
        protected QuizService $quizService
    ) {}

    // buat quiz
    public function store(StoreQuizRequest $request, CourseModule $module)
    {

        $quiz = $this->quizService->createQuiz(
            $module,
            $request->validated()
        );

        return redirect()
            ->route('modules.show', $module)
            ->with('success', 'Quiz berhasil dibuat');
    }

    // lihat quiz
    public function show(ModuleQuiz $quiz)
    {
        $this->authorize('view', $quiz);

        return view('quiz.show', compact('quiz'));
    }


    // edit quiz
    public function update(UpdateQuizRequest $request, ModuleQuiz $quiz)
    {
        $this->quizService->updateQuiz(
            $quiz,
            $request->validated()
        );

        return back()->with('success', 'Quiz berhasil diperbarui');
    }

    // arsipkan quiz
    public function archive(ModuleQuiz $quiz)
    {
        $this->authorize('update', $quiz);

        $this->quizService->archiveQuiz($quiz);

        return back()->with('success', 'Quiz dinonaktifkan');
    }
}
