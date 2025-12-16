<?php

namespace App\Http\Requests\CourseType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CourseTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageCourseTypes() ?? false; // sesuaikan role admin
    }

    protected function prepareForValidation(): void
    {
        // kalau slug kosong, auto-generate dari name
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:course_types,name'],
            'slug' => ['required', 'string', 'max:120', 'unique:course_types,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Nama type course sudah ada.',
            'slug.unique' => 'Slug type course sudah ada.',
        ];
    }
}
