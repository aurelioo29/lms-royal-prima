<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'type',
        'content',
        'file_path',
        'sort_order',
        'is_required',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progresses()
    {
        return $this->hasMany(ModuleProgress::class);
    }
}
