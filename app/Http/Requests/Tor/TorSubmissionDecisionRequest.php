<?php

namespace App\Http\Requests\Tor;

use Illuminate\Foundation\Http\FormRequest;

class TorSubmissionDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canApproveTOR() ?? false;
    }

    public function rules(): array
    {
        return [
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
