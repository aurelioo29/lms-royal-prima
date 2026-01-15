<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestionOption extends Model
{
    protected $fillable = [
        'quiz_question_id',
        'option_text',
        'is_correct',
        'sort_order',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
