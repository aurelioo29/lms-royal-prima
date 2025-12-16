<?php

namespace App\Http\Requests\PlanEvent;

use Illuminate\Foundation\Http\FormRequest;

class PlanEventDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->canApprovePlans() ?? false;
    }

    public function rules(): array
    {
        return [
            'rejected_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
