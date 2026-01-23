<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Services\Quiz\QuizAttemptService;
use App\Models\QuizAttempt;
use App\Models\CourseModule;
use App\Models\Course;
use DomainException;


class QuizAttemptController extends Controller
{

    public function __construct(
        protected QuizAttemptService $quizService
    ) {}

    /**
     * ============================
     * HALAMAN MULAI QUIZ (CONFIRM)
     * ============================
     */
    public function startPage(Course $course, CourseModule $module)
    {
        // keamanan relasi
        abort_unless($module->course_id === $course->id, 404);

        $quiz = $module->quiz;
        abort_if(! $quiz, 404, 'Quiz tidak ditemukan');

        $this->authorize('start', $quiz);

        // cek attempt aktif (resume)
        $activeAttempt = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->first();

        return view('quiz.start', compact(
            'course',
            'module',
            'quiz',
            'activeAttempt'
        ));
    }

    /**
     * ============================
     * START QUIZ (CREATE ATTEMPT)
     * ============================
     */
    public function start(Course $course, CourseModule $module)
    {
        abort_unless($module->course_id === $course->id, 404);

        $quiz = $module->quiz;
        abort_if(! $quiz, 404);

        $this->authorize('start', $quiz);

        $user = auth()->user();

        // cek attempt aktif
        $attempt = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNull('submitted_at')
            ->first();

        // buat attempt baru jika belum ada
        if (! $attempt) {
            try {
                $attempt = $this->quizService->startQuiz($quiz, $user);
            } catch (DomainException $e) {
                return redirect()
                    ->route('employee.courses.modules.show', [$course->id, $module->id])
                    ->withErrors([
                        'quiz' => $e->getMessage()
                    ]);
            }
        }

        return redirect()->route(
            'employee.courses.modules.quiz.attempt',
            [$course->id, $module->id, $attempt->id]
        );
    }

    /**
     * ============================
     * HALAMAN PENGERJAAN QUIZ
     * ============================
     */
    public function attempt(
        Course $course,
        CourseModule $module,
        QuizAttempt $attempt
    ) {
        abort_unless(
            $attempt->quiz->course_module_id === $module->id,
            404
        );

        abort_if(
            $attempt->status !== 'started',
            403,
            'Quiz sudah dikumpulkan'
        );

        $this->authorize('attempt', $attempt);


        $quiz = $attempt->quiz->load('questions.options');

        return view('quiz.attempt', compact('course', 'module', 'attempt', 'quiz'));
    }

    /**
     * ============================
     * SUBMIT QUIZ
     * ============================
     */
    public function submit(
        SubmitQuizRequest $request,
        Course $course,
        CourseModule $module,
        QuizAttempt $attempt
    ) {
        abort_unless($module->course_id === $course->id, 404);
        abort_unless(
            $attempt->quiz->course_module_id === $module->id,
            404
        );


        $this->authorize('submit', $attempt);

        $this->quizService->submitQuiz(
            $attempt,
            $request->validated()['answers']
        );

        return redirect()->route(
            'employee.courses.modules.quiz.result',
            [$course->id, $module->id, $attempt->id]
        )->with('success', 'Quiz berhasil dikumpulkan');
    }

    /**
     * ============================
     * HASIL QUIZ
     * ============================
     */
    public function result(Course $course, CourseModule $module, QuizAttempt $attempt)
    {
        // 🔐 Security: attempt harus milik user
        abort_if($attempt->user_id !== auth()->id(), 403);

        // 🔐 Pastikan sudah submit
        if (! in_array($attempt->status, [
            'submitted',
            'reviewed_passed',
            'reviewed_failed',
        ])) {
            return redirect()
                ->route(
                    'employee.courses.modules.quiz.attempt',
                    [$course->id, $module->id, $attempt->id]
                )
                ->with('error', 'Quiz belum diselesaikan.');
        }

        $attempt->load([
            'answers.option',
            'answers.question',
        ]);


        $quiz = $attempt->quiz->load('questions.options');

        return view('quiz.result', compact(
            'course',
            'module',
            'attempt',
            'quiz'
        ));
    }
}
