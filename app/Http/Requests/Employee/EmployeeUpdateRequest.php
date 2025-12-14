<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id; // route model binding

        return [
            'name'            => ['required', 'string', 'max:255'],
            'nik'             => ['required', 'string', 'max:50', Rule::unique('users', 'nik')->ignore($employeeId)],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employeeId)],
            'phone'           => ['required', 'string', 'max:30'],
            'birth_date'      => ['required', 'date'],
            'gender'          => ['required', Rule::in(['M', 'F'])],
            'job_category_id' => ['required', 'integer', 'exists:job_categories,id'],
            'job_title_id'    => ['required', 'integer', 'exists:job_titles,id'],
            'is_active'       => ['required', Rule::in(['0', '1', 0, 1, true, false])],

            // optional saat edit
            'password'        => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
