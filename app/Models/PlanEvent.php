<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanEvent extends Model
{
    protected $fillable = [
        'annual_plan_id',
        'course_id',

        'mode',         // online|offline|blended|null
        'meeting_link', // url|null

        'status',       // scheduled|cancelled|done
        'target_audience',
        'notes',
    ];

    public function annualPlan(): BelongsTo
    {
        return $this->belongsTo(AnnualPlan::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(PlanEventSession::class);
    }

    public function isOnline(): bool
    {
        return in_array($this->mode, ['online', 'blended'], true);
    }
}
