<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'content',
        'file_path',
        'sort_order',
        'is_active',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progresses()
    {
        return $this->hasMany(ModuleProgress::class);
    }

    public function quiz()
    {
        return $this->hasOne(ModuleQuiz::class);
    }
}
