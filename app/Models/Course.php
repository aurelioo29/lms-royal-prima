<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'tor_submission_id',
        'course_type_id',

        'title',
        'description',
        'training_hours',

        'status',     // draft|published|archived
        'created_by', // admin
    ];

    public function torSubmission(): BelongsTo
    {
        return $this->belongsTo(TorSubmission::class, 'tor_submission_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
