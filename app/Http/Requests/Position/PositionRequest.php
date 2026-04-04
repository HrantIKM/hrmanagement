<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'department_id' => $this->department_id === '' ? null : $this->department_id,
            'min_salary' => $this->min_salary === '' ? null : $this->min_salary,
            'max_salary' => $this->max_salary === '' ? null : $this->max_salary,
            'grade_level' => $this->grade_level === '' ? null : $this->grade_level,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string_with_max',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::when($this->filled('min_salary'), ['gte:min_salary']),
            ],
            'grade_level' => 'nullable|string|max:64',
            'department_id' => 'nullable|exist_validator:departments,id',
        ];
    }
}
