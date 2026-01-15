<?php

namespace App\Services\Quiz;

use App\Models\ModuleQuiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\QuizAnswer;
use App\Models\CourseModule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class QuizService
{
    // buat quiz
    public function createQuiz(CourseModule $module, array $data): ModuleQuiz
    {
        return ModuleQuiz::create([
            'course_module_id' => $module->id,
            'title'            => $data['title'],
            'passing_score'    => $data['passing_score'],
            'time_limit'       => $data['time_limit'] ?? null,
            'is_mandatory'     => $data['is_mandatory'] ?? true,
            'is_active'        => true,
        ]);
    }

    // update quiz
    public function updateQuiz(ModuleQuiz $quiz, array $data): void
    {
        $quiz->update($data);
    }

    // arsipkan quiz
    public function archiveQuiz(ModuleQuiz $quiz): void
    {
        $quiz->update(['is_active' => false]);
    }

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
        // Cegah double submit
        if ($attempt->submitted_at) {
            throw new \Exception('Quiz sudah disubmit sebelumnya');
        }

        // Validasi time limit (jika ada)
        if ($attempt->quiz->time_limit) {
            $expiredAt = Carbon::parse($attempt->started_at)
                ->addMinutes($attempt->quiz->time_limit);

            if (now()->greaterThan($expiredAt)) {
                throw new \Exception('Waktu quiz sudah habis');
            }
        }

        return DB::transaction(function () use ($attempt, $answers) {

            $totalScore = 0;

            foreach ($attempt->quiz->questions as $question) {

                // pastikan jawaban memang untuk soal ini
                $userAnswer = $answers[$question->id] ?? null;

                $score = 0;
                $isCorrect = false;

                if ($question->type === 'mcq' && $userAnswer) {
                    $option = $question->options()
                        ->where('id', $userAnswer)
                        ->where('is_correct', true)
                        ->first();

                    if ($option) {
                        $score = $question->score;
                        $isCorrect = true;
                    }
                }

                // Essay / type lain → score default 0 (manual review nanti)
                QuizAnswer::create([
                    'quiz_attempt_id'         => $attempt->id,
                    'quiz_question_id'        => $question->id,
                    'quiz_question_option_id' => $question->type === 'mcq' ? $userAnswer : null,
                    'answer_text'             => $question->type === 'essay' ? $userAnswer : null,
                    'score'                   => $score,
                    'is_correct'              => $isCorrect,
                ]);

                $totalScore += $score;
            }

            $passed = $totalScore >= $attempt->quiz->passing_score;

            $attempt->update([
                'score'        => $totalScore,
                'is_passed'    => $passed,
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
