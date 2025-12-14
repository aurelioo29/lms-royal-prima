<?php

namespace App\Http\Requests\InstructorDocument;

use Illuminate\Foundation\Http\FormRequest;

class UploadMotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // auth only
    }

    public function rules(): array
    {
        return [
            'mot_file'   => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'issued_at'  => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:issued_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'mot_file.required' => 'File MOT wajib diupload.',
            'mot_file.mimes'    => 'Format harus PDF/JPG/PNG.',
            'mot_file.max'      => 'Ukuran file maksimal 5MB.',
        ];
    }
}
