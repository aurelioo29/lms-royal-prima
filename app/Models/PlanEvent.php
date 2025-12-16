<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanEvent extends Model
{
    protected $fillable = [
        'annual_plan_id',

        'course_id',      // NEW
        'mode',           // NEW (online|offline|blended|null)
        'meeting_link',   // NEW

        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'target_audience',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function annualPlan(): BelongsTo
    {
        return $this->belongsTo(AnnualPlan::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // helpers optional (biar enak dipakai di blade)
    public function isOnline(): bool
    {
        return in_array($this->mode, ['online', 'blended'], true);
    }
}
