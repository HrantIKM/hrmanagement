<?php

namespace App\Http\Requests\Skill;

use App\Http\Requests\Core\DatatableSearchRequest;

class SkillSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.id' => 'nullable|integer_with_max',
            'f.name' => 'nullable|string_with_max',
            'f.category' => 'nullable|string_with_max',
            'f.department_id' => 'nullable|integer_with_max|exists:departments,id',
        ];
    }
}
