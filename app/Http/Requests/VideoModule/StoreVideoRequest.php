<?php

namespace App\Http\Requests\VideoModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()?->role?->name === 'Developer';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_url' => ['required', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
