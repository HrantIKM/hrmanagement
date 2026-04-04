<?php

namespace App\Http\Requests\User;

use App\Models\User\Enums\EmploymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'department_id' => $this->department_id === '' ? null : $this->department_id,
            'position_id' => $this->position_id === '' ? null : $this->position_id,
            'hire_date' => $this->hire_date === '' ? null : $this->hire_date,
            'salary' => $this->salary === '' ? null : $this->salary,
        ]);
    }

    public function rules(): array
    {
        $passwordRule = $this->user ? 'nullable' : 'required';

        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',

            'email' => 'required|email_validator|unique:users,email,' . $this->user?->id,
            'avatar' => 'required|string_with_max',

            'role_ids' => 'required|array',
            'role_ids.*' => 'required|exist_validator:roles,id',

            'department_id' => 'nullable|exist_validator:departments,id',
            'position_id' => 'nullable|exist_validator:positions,id',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'employment_status' => ['required', Rule::in(EmploymentStatus::ALL)],

            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exist_validator:skills,id',

            'password' => $passwordRule . '|string|min:6|string_with_max|confirmed',
            'password_confirmation' => $passwordRule . '|string_with_max',
        ];
    }
}
