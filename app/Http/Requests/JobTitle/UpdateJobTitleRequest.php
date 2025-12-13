<?php

namespace App\Http\Requests\JobTitle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('job_title')?->id;

        return [
            'job_category_id' => ['required', 'integer', 'exists:job_categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9_]+$/', Rule::unique('job_titles', 'slug')->ignore($id)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
