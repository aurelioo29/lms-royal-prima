<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompletion extends Model
{
    use HasFactory;

    protected $table = 'course_completions';

    protected $fillable = [
        'course_id',
        'user_id',
        'earned_hours',
        'completed_at',
        'certificate_number',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'earned_hours' => 'decimal:2',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: filter completion berdasarkan user
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope: filter completion berdasarkan course
    public function scopeByCourse($query, int $courseId)
    {
        return $query->where('course_id', $courseId);
    }
}
