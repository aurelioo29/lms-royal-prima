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

            'type' => ['required', 'in:pdf,video,link,quiz'],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,mp4',
                'max:20480',
                function ($attr, $value, $fail) {
                    if ($this->type === 'pdf' && ! $this->hasFile('file')) {
                        $fail('File PDF wajib diunggah.');
                    }
                }
            ],

            'content' => [
                'nullable',
                'string',
                function ($attr, $value, $fail) {
                    if (in_array($this->type, ['video', 'link']) && empty($value)) {
                        $fail('Konten wajib diisi untuk tipe video atau link.');
                    }
                }
            ],


            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_required' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_required' => $this->boolean('is_required'),
            'is_active'   => $this->boolean('is_active'),
        ]);
    }
}
