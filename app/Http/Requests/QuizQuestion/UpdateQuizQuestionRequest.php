<?php

namespace App\Http\Requests\QuizQuestion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizQuestionRequest extends FormRequest
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
            'question' => ['required', 'string'],
            'type'     => ['required', 'in:mcq,true_false,essay'],
            'score'    => ['required', 'integer', 'min:1'],

            'options' => [
                'required_if:type,mcq,true_false',
                'array',
                'min:2',
            ],

            'options.*.text' => [
                'required_if:type,mcq,true_false',
                'string',
            ],

            'correct_index' => [
                'required_if:type,mcq,true_false',
                'integer',
                'min:0',
            ],
        ];
    }
}
