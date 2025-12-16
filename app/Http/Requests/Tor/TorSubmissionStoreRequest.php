<?php

namespace App\Http\Requests\Tor;

use Illuminate\Foundation\Http\FormRequest;

class TorSubmissionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canCreateTOR() ?? false;
    }

    public function rules(): array
    {
        return [
            'plan_event_id' => ['required', 'integer', 'exists:plan_events,id'],

            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],

            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }
}
