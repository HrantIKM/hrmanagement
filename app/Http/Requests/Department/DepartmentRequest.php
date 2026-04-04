<?php

namespace App\Http\Requests\Department;

use App\Models\Department\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function rules(): array
    {
        $department = $this->route('department');

        return [
            'name' => [
                'required',
                'string_with_max',
                Rule::unique(Department::getTableName(), 'name')->ignore($department),
            ],
            'description' => 'nullable|string_with_max',
            'icon' => 'required|string_with_max',
        ];
    }
}
