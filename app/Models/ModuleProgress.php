<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleProgress extends Model
{
    protected $fillable = [
        'course_module_id',
        'user_id',
        'status',
        'completed_at',
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
