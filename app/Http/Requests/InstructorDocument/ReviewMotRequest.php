<?php

namespace App\Http\Requests\InstructorDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewMotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canApproveMot() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'rejected_reason' => ['nullable', 'string', 'max:500', 'required_if:status,rejected'],
        ];
    }

    public function messages(): array
    {
        return [
            'rejected_reason.required_if' => 'Alasan penolakan wajib diisi jika ditolak.',
        ];
    }
}
