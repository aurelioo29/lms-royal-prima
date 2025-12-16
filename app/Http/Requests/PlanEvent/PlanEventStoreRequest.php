<?php

namespace App\Http\Requests\PlanEvent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanEventStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreatePlans() ?? false;
    }

    public function rules(): array
    {
        return [
            // relation
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],

            // schedule metadata
            'mode' => ['nullable', Rule::in(['online', 'offline', 'blended'])],
            'meeting_link' => [
                'nullable',
                'url',
                // kalau mode online/blended, meeting_link wajib
                Rule::requiredIf(fn() => in_array($this->input('mode'), ['online', 'blended'], true)),
                'max:255',
            ],

            // existing fields
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['scheduled', 'cancelled', 'done'])],
        ];
    }

    public function messages(): array
    {
        return [
            'meeting_link.required' => 'Meeting link wajib diisi untuk mode online/blended.',
            'meeting_link.url' => 'Meeting link harus berupa URL yang valid.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }
}
