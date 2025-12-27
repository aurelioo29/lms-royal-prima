<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanEventInstructor extends Model
{
    protected $fillable = [
        'plan_event_id',
        'user_id',
        'role',
        'teaching_hours',
        'status',
    ];

    public function planEvent()
    {
        return $this->belongsTo(PlanEvent::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
