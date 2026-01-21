<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0',
            'time_limit'    => 'nullable|integer|min:1',
            'is_mandatory'  => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_mandatory' => $this->boolean('is_mandatory'),
        ]);
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul quiz wajib diisi',
            'passing_score.required' => 'Passing score wajib diisi',
            'passing_score.integer'  => 'Passing score harus berupa angka',
        ];
    }
}
