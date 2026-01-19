<?php

namespace App\Http\Requests\QuizQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizQuestionRequest extends FormRequest
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
            'type'        => 'required|in:multiple_choice,essay',
            'question'    => 'required|string',
            'score'       => 'required|integer|min:1',
            'options'     => 'required_if:type,multiple_choice|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'boolean'
        ];
    }
}
