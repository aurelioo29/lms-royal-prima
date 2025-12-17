<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
