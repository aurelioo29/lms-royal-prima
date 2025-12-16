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
            'annual_plan_id' => ['required', 'integer', 'exists:annual_plans,id'],

            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],

            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],

            'location' => ['nullable', 'string', 'max:255'],
            'target_audience' => ['nullable', 'string', 'max:255'],

            'mode' => ['nullable', Rule::in(['online', 'offline', 'blended'])],
            'meeting_link' => [
                'nullable',
                'url',
                Rule::requiredIf(
                    fn() =>
                    in_array($this->input('mode'), ['online', 'blended'], true)
                ),
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'meeting_link.required' => 'Meeting link wajib diisi untuk mode online / blended.',
        ];
    }
}
