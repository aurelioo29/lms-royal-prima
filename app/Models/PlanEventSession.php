<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanEventSession extends Model
{
    protected $fillable = [
        'plan_event_id',
        'date',
        'start_time',
        'end_time',
        'location',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function planEvent(): BelongsTo
    {
        return $this->belongsTo(PlanEvent::class);
    }
}
