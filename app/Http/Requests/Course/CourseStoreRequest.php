<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canCreateCourses();
    }

    public function rules(): array
    {
        return [
            'tor_submission_id' => ['required', 'integer', 'exists:tor_submissions,id'],
            'course_type_id' => ['nullable', 'integer', 'exists:course_types,id'],
            'tujuan' => ['nullable', 'string', 'max:5000'],
            'training_hours' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
