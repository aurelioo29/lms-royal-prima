<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'tor_submission_id',
        'course_type_id',
        'tujuan',
        'training_hours',
        'status',
        'created_by',
        // enrollment_key is auto-filled
    ];

    protected $casts = [
        'training_hours' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            // Generate unique enrollment_key if not set
            if (!$course->enrollment_key) {
                do {
                    // Example format: "PEL-8F3KQ2XJ"
                    $key = 'PEL-' . strtoupper(Str::random(8));
                } while (self::where('enrollment_key', $key)->exists());

                $course->enrollment_key = $key;
            }
        });
    }

    public function torSubmission(): BelongsTo
    {
        return $this->belongsTo(TorSubmission::class, 'tor_submission_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    // Convenience “live” getters (no DB columns)
    public function getEventTitleAttribute(): string
    {
        return $this->torSubmission?->planEvent?->title ?? '—';
    }

    public function getEventDescriptionAttribute(): ?string
    {
        return $this->torSubmission?->planEvent?->description;
    }
}
