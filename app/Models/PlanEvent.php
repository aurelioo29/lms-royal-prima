<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanEvent extends Model
{
    protected $fillable = [
        'annual_plan_id',
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
}
