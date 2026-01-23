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
        'reviewed_by',
        'reviewed_at',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'is_passed'   => 'boolean',
        'started_at'  => 'datetime',
        'submitted_at' => 'datetime',
        'expired_at'  => 'datetime',
        'reviewed_at' => 'datetime',
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

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /* ================= DOMAIN HELPERS ================= */

    public function isStarted(): bool
    {
        return $this->status === 'started';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isReviewed(): bool
    {
        return in_array($this->status, [
            'reviewed_passed',
            'reviewed_failed',
        ]);
    }

    protected static function booted()
    {
        static::creating(function ($attempt) {
            $attempt->status = 'started';
        });
    }


    public function isPassed(): bool
    {
        return $this->status === 'reviewed_passed';
    }

    // Recalculate score berdasarkan jawaban dan review
    public function recalculateScore(): void
    {
        $this->loadMissing([
            'answers.question',
            'answers.option',
            'answers.review',
            'quiz',
        ]);

        $total = 0;

        foreach ($this->answers as $answer) {

            // ================= ESSAY =================
            if ($answer->question->type === 'essay') {

                // WAJIB: nilai dari REVIEW
                if (
                    $answer->review &&
                    $answer->review->is_correct === true
                ) {
                    $total += $answer->question->score;
                }
            }

            // ================= MCQ / TRUE_FALSE =================
            if (in_array($answer->question->type, ['mcq', 'true_false'])) {
                if ($answer->option && $answer->option->is_correct) {
                    $total += $answer->question->score;
                }
            }
        }

        $passed = $total >= $this->quiz->passing_score;

        $this->update([
            'score'       => $total,
            'is_passed'   => $passed,
            'status'      => $passed
                ? 'reviewed_passed'
                : 'reviewed_failed',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);
    }
}
