<?php

namespace App\Http\Requests\Skill;

use App\Models\Skill\Enums\SkillCategory;
use App\Models\Skill\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkillRequest extends FormRequest
{
    public function rules(): array
    {
        $skill = $this->route('skill');

        return [
            'name' => [
                'required',
                'string_with_max',
                Rule::unique(Skill::getTableName(), 'name')->ignore($skill),
            ],
            'category' => ['required', Rule::in(SkillCategory::ALL)],
        ];
    }
}
