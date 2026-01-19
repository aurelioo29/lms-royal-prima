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
            'questions' => ['required', 'array', 'min:1'],

            'questions.*.question' => ['required', 'string'],
            'questions.*.type'     => ['required', 'in:mcq,true_false,essay'],
            'questions.*.score'    => ['nullable', 'integer', 'min:1'],

            // OPTIONS → hanya untuk mcq & true_false
            'questions.*.options' => [
                'required_if:questions.*.type,mcq,true_false',
                'array',
                'min:2',
            ],

            'questions.*.options.*.text' => [
                'required_if:questions.*.type,mcq,true_false',
                'string',
            ],

            'questions.*.correct_index' => [
                'required_if:questions.*.type,mcq,true_false',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.required' => 'Minimal harus ada satu soal.',
            'questions.*.question.required' => 'Teks soal wajib diisi.',
            'questions.*.options.min' => 'Minimal dua opsi jawaban.',
            'questions.*.correct_index.required' => 'Jawaban benar harus dipilih.',
        ];
    }
}
