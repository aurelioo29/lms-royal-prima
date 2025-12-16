<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TorSubmission extends Model
{
    protected $fillable = [
        'plan_event_id',

        'title',
        'content',
        'file_path',

        'status',       // draft|submitted|approved|rejected
        'created_by',

        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function planEvent(): BelongsTo
    {
        return $this->belongsTo(PlanEvent::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
