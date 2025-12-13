<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/', Rule::unique('roles', 'slug')],
            'level' => ['required', 'integer', 'min:0', 'max:999'],
            'can_manage_users' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan underscore. Contoh: head_training',
        ];
    }
}
