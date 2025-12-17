<?php

namespace App\Http\Requests\CourseType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        // handle route model binding: {course_type}
        $courseType = $this->route('course_type');
        $id = is_object($courseType) ? $courseType->id : $courseType;

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('course_types', 'name')->ignore($id)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama type course sudah ada.',
        ];
    }
}
