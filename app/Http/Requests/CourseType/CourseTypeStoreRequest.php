<?php

namespace App\Http\Requests\CourseType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CourseTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
