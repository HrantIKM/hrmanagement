<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\Core\DatatableSearchRequest;

class DepartmentSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.name' => 'nullable|string_with_max',
                'f.description' => 'nullable|string_with_max',
            ];
    }
}
