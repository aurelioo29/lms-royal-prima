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
        'max_attempts',
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

    public function canBeActivated(): bool
    {
        return $this->questions()->where('is_active', true)->count() > 0;
    }

    public function isUnlimitedAttempts(): bool
    {
        return is_null($this->max_attempts);
    }

    protected $casts = [
        'max_attempts' => 'integer',
        'is_mandatory' => 'boolean',
    ];
}
