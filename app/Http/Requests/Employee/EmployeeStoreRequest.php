<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah di-protect middleware can_manage_users
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'nik'             => ['required', 'string', 'max:50', Rule::unique('users', 'nik')],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone'           => ['required', 'string', 'max:30'],
            'birth_date'      => ['required', 'date'],
            'gender'          => ['required', Rule::in(['M', 'F'])],
            'job_category_id' => ['required', 'integer', 'exists:job_categories,id'],
            'job_title_id'    => ['required', 'integer', 'exists:job_titles,id'],
            'is_active'       => ['required', Rule::in(['0', '1', 0, 1, true, false])],

            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Lengkap',
            'nik' => 'NIK',
            'phone' => 'No Handphone',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'job_category_id' => 'Job Category',
            'job_title_id' => 'Job Title',
            'is_active' => 'Status',
            'password' => 'Password',
        ];
    }
}
