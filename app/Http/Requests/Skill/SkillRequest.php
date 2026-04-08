<?php

namespace App\Http\Requests\Skill;

use App\Models\Department\Enums\DepartmentCode;
use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillRequest extends FormRequest
{
    public function rules(): array
    {
        $skill = $this->route('skill');
        $departmentId = $this->input('department_id');

        return [
            'department_id' => [
                'required',
                'integer_with_max',
                Rule::exists('departments', 'id')->where(function ($query) {
                    $query->whereIn('name', DepartmentCode::values());
                }),
            ],
            'name' => [
                'required',
                'string_with_max',
                Rule::unique(Skill::getTableName(), 'name')
                    ->where(fn($query) => $query->where('department_id', $departmentId))
                    ->ignore($skill),
            ],
            'category' => ['required', Rule::in(SkillCategory::ALL)],
        ];
    }
}
