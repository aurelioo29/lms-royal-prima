<?php

namespace App\Http\Requests\CourseModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseModuleRequest extends FormRequest
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
                    if ($this->type === 'pdf') {

                        if (! $this->hasFile('file')) {
                            $fail('File PDF wajib diunggah.');
                            return;
                        }

                        // 20MB untuk PDF
                        if ($value->getSize() > 20 * 1024 * 1024) {
                            $fail('Ukuran PDF maksimal 20MB.');
                        }
                    }

                    // ================= VIDEO UPLOAD =================
                    if ($this->type === 'video' && $this->video_mode === 'upload') {

                        if (! $this->hasFile('file')) {
                            $fail('File video wajib diunggah.');
                            return;
                        }

                        // 100MB untuk Video
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

            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],


            // ================= QUIZ =================

            'has_quiz' => ['nullable', 'boolean'],


            'quiz.title' => [
                'nullable',
                'string',
                'max:255',
                function ($attr, $value, $fail) {
                    if ($this->boolean('has_quiz') && empty($value)) {
                        $fail('Judul kuis wajib diisi.');
                    }
                }
            ],

            'quiz.description' => ['nullable', 'string'],

            'quiz.passing_score' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
                function ($attr, $value, $fail) {
                    if ($this->boolean('has_quiz') && empty($value)) {
                        $fail('Passing score wajib diisi.');
                    }
                }
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
        $this->merge([
            'has_quiz' => $this->boolean('has_quiz'),
            'is_required' => $this->boolean('is_required'),
            'is_active'   => $this->boolean('is_active'),

            // quiz normalization
            'quiz' => array_merge($this->input('quiz', []), [
                'is_mandatory' => data_get($this->quiz, 'is_mandatory', false),
                'status'       => data_get($this->quiz, 'status', 'draft'),
            ]),
        ]);
    }
}
