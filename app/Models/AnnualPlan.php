<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualPlan extends Model
{
    protected $fillable = [
        'annual_plan_id',
        'year',
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'target_audience',
        'mode',
        'meeting_link',
        'status',
        'created_by',
    ];

    protected $casts = [
        'year' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function events()
    {
        return $this->hasMany(\App\Models\PlanEvent::class, 'annual_plan_id');
    }

    public function planEvents(): HasMany
    {
        return $this->events();
    }

    // helpers
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    /**
     * Event yang belum punya TOR (atau TOR belum submitted/approved kalau pakai versi ketat)
     */
    public function missingTorEvents()
    {
        return $this->events()
            ->whereDoesntHave('torSubmission', function ($q) {
                $q->whereIn('status', ['submitted', 'approved']);
            })
            ->get();
    }
}
