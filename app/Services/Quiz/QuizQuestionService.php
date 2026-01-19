<?php

namespace App\Services\Quiz;

use App\Models\ModuleQuiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\DB;

class QuizQuestionService
{
    public function create(ModuleQuiz $quiz, array $data): void
    {
        DB::transaction(function () use ($quiz, $data) {

            foreach ($data['questions'] as $index => $q) {

                $question = $quiz->questions()->create([
                    'question'   => $q['question'],
                    'type'       => $q['type'], // mcq | true_false | essay
                    'score'      => $q['score'] ?? 10,
                    'sort_order' => $index + 1,
                    'is_active'  => true,
                ]);

                // Simpan opsi hanya untuk mcq & true_false
                if (in_array($q['type'], ['mcq', 'true_false'])) {
                    $this->storeOptions($question, $q);
                }
            }
        });
    }

    public function update(QuizQuestion $question, array $data): QuizQuestion
    {
        return DB::transaction(function () use ($question, $data) {

            // Update basic fields
            $question->update([
                'question' => $data['question'],
                'score'    => $data['score'],
            ]);

            // Jika tipe berubah → reset opsi
            if ($data['type'] !== $question->type) {
                $question->options()->delete();
                $question->update([
                    'type' => $data['type'],
                ]);
            }

            // MCQ / TRUE_FALSE → simpan opsi
            if (
                in_array($question->type, ['mcq', 'true_false'], true)
                && isset($data['options'])
            ) {
                $question->options()->delete();
                $this->storeOptions($question, $data);
            }

            // ESSAY → pastikan tidak punya opsi
            if ($question->type === 'essay') {
                $question->options()->delete();
            }

            return $question;
        });
    }

    public function delete(QuizQuestion $question): void
    {
        DB::transaction(function () use ($question) {
            $question->options()->delete();
            $question->delete();
        });
    }

    protected function storeOptions(QuizQuestion $question, array $data): void
    {
        if (
            !isset($data['options']) ||
            !isset($data['correct_index'])
        ) {
            return;
        }

        $correctIndex = (int) $data['correct_index'];

        foreach ($data['options'] as $index => $option) {
            $question->options()->create([
                'option_text' => $option['text'],
                'is_correct'  => $index === $correctIndex,
                'sort_order'  => $index + 1,
            ]);
        }
    }

    public function sync(ModuleQuiz $quiz, array $data): void
    {
        DB::transaction(function () use ($quiz, $data) {

            $incomingIds = [];

            foreach ($data['questions'] as $index => $q) {

                $question = $quiz->questions()->updateOrCreate(
                    ['id' => $q['id'] ?? null],
                    [
                        'question'   => $q['question'],
                        'type'       => $q['type'],
                        'score'      => $q['score'] ?? 10,
                        'sort_order' => $index + 1,
                        'is_active'  => true,
                    ]
                );

                $incomingIds[] = $question->id;

                // Reset opsi
                $question->options()->delete();

                if (in_array($q['type'], ['mcq', 'true_false'], true)) {
                    $this->storeOptions($question, $q);
                }
            }

            // Hapus soal yang tidak lagi dikirim
            $quiz->questions()
                ->whereNotIn('id', $incomingIds)
                ->delete();
        });
    }
}
