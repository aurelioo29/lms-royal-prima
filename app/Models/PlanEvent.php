<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanEvent extends Model
{
    protected $fillable = [
        'annual_plan_id',

        'title',
        'description',

        'start_date',
        'end_date',
        'start_time',
        'end_time',

        'location',
        'target_audience',

        'mode',         // online|offline|blended|null
        'meeting_link', // url|null

        // approval flow
        'status',        // draft|pending|approved|rejected
        'created_by',
        'approved_by',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'rejected_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function annualPlan()
    {
        return $this->belongsTo(AnnualPlan::class, 'annual_plan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Kalau kamu mau 1 event = 1 TOR (paling umum), pakai hasOne.
     * Kalau mau TOR bisa banyak revisi, ganti ke hasMany.
     */
    public function torSubmission(): HasOne
    {
        return $this->hasOne(TorSubmission::class);
    }

    // kalau 1 event bisa punya banyak TOR:
    // public function torSubmissions(): HasMany { return $this->hasMany(TorSubmission::class); }

    public function isOnline(): bool
    {
        return in_array($this->mode, ['online', 'blended'], true);
    }

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
     * Helper: cek apakah sebuah tanggal termasuk range event ini.
     */
    public function containsDate(string|\DateTimeInterface $date): bool
    {
        $d = \Illuminate\Support\Carbon::parse($date)->startOfDay();
        return $d->between($this->start_date, $this->end_date);
    }
}
