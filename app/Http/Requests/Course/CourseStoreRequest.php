<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreateCourses() ?? false;
    }

    public function rules(): array
    {
        return [
            'tor_submission_id' => ['required', 'integer', 'exists:tor_submissions,id'],

            'course_type_id' => [
                'nullable',
                'integer',
                Rule::exists('course_types', 'id')
                    ->where(fn($q) => $q->where('is_active', true)),
            ],

            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'training_hours' => ['required', 'numeric', 'min:0', 'max:999.99'],

            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
