<?php

namespace App\Services\Quiz;

use App\Models\ModuleQuiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\DB;

class QuizQuestionService
{
    public function create(ModuleQuiz $quiz, array $data): QuizQuestion
    {
        return DB::transaction(function () use ($quiz, $data) {

            $question = $quiz->questions()->create([
                'type'     => $data['type'],
                'question' => $data['question'],
                'score'    => $data['score'],
            ]);

            if ($question->type === 'multiple_choice') {
                foreach ($data['options'] as $option) {
                    $question->options()->create($option);
                }
            }

            return $question;
        });
    }

    public function update(QuizQuestion $question, array $data): QuizQuestion
    {
        return DB::transaction(function () use ($question, $data) {

            $question->update($data);

            if ($question->type === 'multiple_choice' && isset($data['options'])) {
                $question->options()->delete();

                foreach ($data['options'] as $option) {
                    $question->options()->create($option);
                }
            }

            return $question;
        });
    }

    public function delete(QuizQuestion $question): void
    {
        $question->delete();
    }
}
