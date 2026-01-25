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
    private function hasReachedMaxAttempts(ModuleQuiz $quiz, User $user): bool
    {
        if ($quiz->isUnlimitedAttempts()) {
            return false;
        }

        $attemptCount = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereNotNull('submitted_at')
            ->count();

        return $attemptCount >= $quiz->max_attempts;
    }


    // mulai kuis
    public function startQuiz(ModuleQuiz $quiz, User $user): QuizAttempt
    {
        // attempt terakhir
        $lastAttempt = QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        // jika ada attempt gagal → reset
        if ($lastAttempt && $lastAttempt->status === 'reviewed_failed') {
            return $this->resetForRetake($lastAttempt);
        }

        // jika sudah lulus → STOP
        if ($lastAttempt && $lastAttempt->is_passed === true) {
            throw new DomainException('Quiz sudah lulus dan tidak dapat dikerjakan ulang.');
        }

        // cek max attempt
        if ($this->hasReachedMaxAttempts($quiz, $user)) {
            throw new DomainException('Jumlah percobaan quiz telah mencapai batas maksimum.');
        }

        // attempt baru
        return QuizAttempt::create([
            'module_quiz_id' => $quiz->id,
            'user_id'        => $user->id,
            'started_at'     => now(),
            'expired_at'     => $quiz->time_limit
                ? now()->addMinutes($quiz->time_limit)
                : null,
            'status'         => 'started',
            'max_score'      => $quiz->questions()->sum('score'),
        ]);
    }



    // submit jawaban kuis
    public function submitQuiz(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        if ($attempt->status !== 'started') {
            throw new DomainException('Attempt tidak aktif');
        }

        if ($attempt->expired_at && now()->greaterThan($attempt->expired_at)) {
            $attempt->update([
                'status'       => 'expired',
                'submitted_at' => now(),
            ]);

            throw new DomainException('Waktu quiz sudah habis');
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

            // cek apakah quiz punya essay
            $hasEssay = $attempt->quiz->questions
                ->contains(fn($q) => $q->type === 'essay');

            $isPassed = $totalScore >= $attempt->quiz->passing_score;

            $attempt->update([
                'score'        => $totalScore,
                'is_passed'    => $hasEssay ? null : $isPassed,
                'submitted_at' => now(),
                'status'       => $hasEssay
                    ? 'submitted'
                    : ($isPassed ? 'reviewed_passed' : 'reviewed_failed'),
            ]);

            return $attempt;
        });
    }

    // cek apakah user lulus module quiz
    public function isModulePassed(User $user, CourseModule $module): bool
    {
        $quiz = $module->quiz;

        if (! $quiz || ! $quiz->is_mandatory) {
            return true;
        }

        return QuizAttempt::where('module_quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['reviewed_passed'])
            ->exists();
    }
}
