<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleQuiz extends Model
{
    protected $fillable = [
        'course_module_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
        'is_mandatory',
        'status',
    ];

    /* ================= RELATIONS ================= */

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /* ================= DOMAIN HELPERS ================= */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
