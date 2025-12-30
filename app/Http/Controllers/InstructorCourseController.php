<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class InstructorCourseController extends Controller
{
    public function index()
    {
        $courses = Course::whereHas('instructors', function ($q) {
            $q->where('user_id', auth()->id())
                ->where('status', 'active');
        })
            ->with(['type', 'torSubmission.planEvent'])
            ->latest()
            ->paginate(10);

        return view('courses.instructor.index', compact('courses'));
    }
}
