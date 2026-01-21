<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = [
        'module_quiz_id',
        'user_id',
        'score',
        'max_score',
        'is_passed',
        'started_at',
        'expired_at',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /* ================= RELATIONS ================= */

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(ModuleQuiz::class, 'module_quiz_id');
    }

    public function moduleQuiz(): BelongsTo
    {
        return $this->belongsTo(ModuleQuiz::class, 'module_quiz_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /* ================= DOMAIN HELPERS ================= */

    public function isFinished(): bool
    {
        return !is_null($this->submitted_at);
    }

    // Recalculate score berdasarkan jawaban dan review
    public function recalculateScore(): void
    {
        $total = 0;

        foreach ($this->answers as $answer) {

            // Essay → ambil dari review
            if ($answer->question->type === 'essay') {
                if ($answer->is_correct) {
                    $total += $answer->question->score;
                }
            }

            // MCQ / TF → auto
            if (in_array($answer->question->type, ['mcq', 'true_false'])) {
                if ($answer->option && $answer->option->is_correct) {
                    $total += $answer->question->score;
                }
            }
        }

        $this->score = $total;
        $this->is_passed = $total >= $this->passing_score;
        $this->save();
    }
}
