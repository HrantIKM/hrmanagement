<?php

namespace App\Http\Requests\Position;

use App\Http\Requests\Core\DatatableSearchRequest;

class PositionSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.title' => 'nullable|string_with_max',
                'f.department_id' => 'nullable|integer_with_max',
            ];
    }
}
