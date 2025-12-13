<?php

namespace App\Http\Requests\JobTitle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public function rules(): array
    {
        return [
            'job_category_id' => ['required', 'integer', 'exists:job_categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9_]+$/', Rule::unique('job_titles', 'slug')],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
