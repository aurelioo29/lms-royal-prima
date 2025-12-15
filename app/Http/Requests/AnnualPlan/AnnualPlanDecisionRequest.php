<?php

namespace App\Http\Requests\AnnualPlan;

use Illuminate\Foundation\Http\FormRequest;

class AnnualPlanDecisionRequest extends FormRequest
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
    