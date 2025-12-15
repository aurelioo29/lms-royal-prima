<?php

namespace App\Http\Requests\PlanEvent;

use Illuminate\Foundation\Http\FormRequest;

class PlanEventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreatePlans() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:scheduled,cancelled,done'],
        ];
    }
}
