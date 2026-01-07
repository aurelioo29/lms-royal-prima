<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseInstructor extends Pivot
{
    protected $table = 'course_instructors';

    protected $fillable = [
        'course_id',
        'user_id',
        'role',
        'status',
        'can_manage_modules',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // narasumber aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // mentor utama
    public function scopeMentor($query)
    {
        return $query->where('role', 'mentor');
    }
}
