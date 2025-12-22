<?php

namespace App\Http\Requests\CourseEnroll;

use Illuminate\Foundation\Http\FormRequest;

class EnrollCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'enrollment_key' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_key.required' => 'Enrollment key wajib diisi.',
        ];
    }
}
