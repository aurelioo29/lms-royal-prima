<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
     protected $fillable = [
        'module_quiz_id',
        'question',
        'type',
        'score',
        'sort_order',
        'is_active',
    ];

    /* ================= RELATIONS ================= */

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(ModuleQuiz::class, 'module_quiz_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizQuestionOption::class);
    }

    /* ================= DOMAIN HELPERS ================= */

    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === 'mcq';
    }
}
