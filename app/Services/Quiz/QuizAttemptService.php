<?php

namespace App\Services\Quiz;

use App\Models\ModuleQuiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\QuizAnswer;
use App\Models\CourseModule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DomainException;


class QuizAttemptService
{


    // mulai kuis
    public function startQuiz(ModuleQuiz $quiz, User $user): QuizAttempt
    {
        $existing = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNull('submitted_at')
            ->first();

        if ($existing) {
            return $existing;
        }

        return QuizAttempt::create([
            'module_quiz_id' => $quiz->id,
            'user_id'        => $user->id,
            'started_at'     => now(),
            'max_score'      => $quiz->questions()->sum('score'),
        ]);
    }


    // submit jawaban kuis
    public function submitQuiz(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        if ($attempt->submitted_at) {
            throw new DomainException('Quiz sudah disubmit');
        }

        // validasi time limit
        if ($attempt->quiz->time_limit) {
            $expiredAt = Carbon::parse($attempt->started_at)
                ->addMinutes($attempt->quiz->time_limit);

            if (now()->greaterThan($expiredAt)) {
                throw new DomainException('Waktu quiz sudah habis');
            }
        }

        return DB::transaction(function () use ($attempt, $answers) {

            $totalScore = 0;

            $attempt->quiz->load('questions.options');

            foreach ($attempt->quiz->questions as $question) {

                $userAnswer = $answers[$question->id] ?? null;

                $score     = 0;
                $isCorrect = false;

                // MCQ & TRUE/FALSE
                if (in_array($question->type, ['mcq', 'true_false']) && $userAnswer) {
                    $option = $question->options
                        ->where('id', $userAnswer)
                        ->where('is_correct', true)
                        ->first();

                    if ($option) {
                        $score     = $question->score;
                        $isCorrect = true;
                    }
                }

                // ESSAY → manual review
                QuizAnswer::create([
                    'quiz_attempt_id'         => $attempt->id,
                    'quiz_question_id'        => $question->id,
                    'quiz_question_option_id' => in_array($question->type, ['mcq', 'true_false'])
                        ? $userAnswer
                        : null,
                    'answer_text' => $question->type === 'essay'
                        ? $userAnswer
                        : null,
                    'score'      => $score,
                    'is_correct' => $isCorrect,
                ]);

                $totalScore += $score;
            }

            $attempt->update([
                'score'        => $totalScore,
                'is_passed'    => $totalScore >= $attempt->quiz->passing_score,
                'submitted_at' => now(),
            ]);

            return $attempt;
        });
    }

    // cek apakah user lulus module quiz
    public function isModulePassed(User $user, CourseModule $module): bool
    {
        $quiz = $module->quiz;

        if (!$quiz || !$quiz->is_mandatory) {
            return true;
        }

        return QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->where('is_passed', true)
            ->exists();
    }
}
