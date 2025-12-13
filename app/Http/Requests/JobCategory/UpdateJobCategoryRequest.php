<?php

namespace App\Http\Requests\JobCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('job_category')?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9_]+$/', Rule::unique('job_categories', 'slug')->ignore($id)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
