<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswerReview extends Model
{
    protected $fillable = [
        'quiz_answer_id',
        'reviewer_id',
        'is_correct',
        'note',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
