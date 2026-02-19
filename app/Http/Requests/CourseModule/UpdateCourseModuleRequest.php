<?php

namespace App\Http\Requests\CourseModule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseModuleRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'type' => ['required', 'in:pdf,video,link'],

            'video_mode' => ['nullable', 'in:link,upload'],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,mp4,mov,avi',
                function ($attr, $value, $fail) {

                    // ================= PDF =================
                    if ($this->type === 'pdf' && $this->hasFile('file')) {

                        if ($value->getSize() > 20 * 1024 * 1024) {
                            $fail('Ukuran PDF maksimal 20MB.');
                        }
                    }

                    // ================= VIDEO UPLOAD =================
                    if ($this->type === 'video' && $this->video_mode === 'upload' && $this->hasFile('file')) {

                        if ($value->getSize() > 100 * 1024 * 1024) {
                            $fail('Ukuran video maksimal 100MB.');
                        }
                    }
                }
            ],

            'content' => [
                'nullable',
                'string',
                function ($attr, $value, $fail) {

                    if ($this->type === 'link' && empty($value)) {
                        $fail('URL wajib diisi untuk tipe link.');
                    }

                    if ($this->type === 'video' && $this->video_mode === 'link' && empty($value)) {
                        $fail('Link video wajib diisi.');
                    }
                }
            ],

            'is_active' => [
                'nullable',
                'boolean',
                function ($attr, $value, $fail) {
                    if ($this->boolean('has_quiz') && $value === true) {
                        $fail('Modul dengan quiz tidak boleh diaktifkan sebelum quiz siap.');
                    }
                }
            ],



            // ================= QUIZ =================

            'has_quiz' => ['nullable', 'boolean'],

            'quiz.title' => [
                'required_if:has_quiz,true',
                'string',
                'max:255',
            ],

            'quiz.description' => ['nullable', 'string'],


            'quiz.passing_score' => [
                'required_if:has_quiz,true',
                'integer',
                'min:0',
                'max:100',
            ],

            'quiz.time_limit' => ['nullable', 'integer', 'min:1'],

            'quiz.max_attempts' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'quiz.is_mandatory' => ['nullable', 'boolean'],

            'quiz.status' => [
                'nullable',
                'in:draft,published,inactive'
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $hasQuiz = $this->boolean('has_quiz');
        $this->merge([
            'has_quiz' => $hasQuiz,
            'is_required' => $this->boolean('is_required'),
            'is_active'   => $hasQuiz
                ? false
                : $this->boolean('is_active'),

            // quiz normalization
            'quiz' => array_merge($this->input('quiz', []), [
                'is_mandatory' => data_get($this->quiz, 'is_mandatory', false),
                'status'       => data_get($this->quiz, 'status', 'draft'),
            ]),
        ]);
    }
}
