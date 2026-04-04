<?php

namespace App\Http\Requests\Vacancy;

use App\Http\Requests\Core\DatatableSearchRequest;

class VacancySearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.title' => 'nullable|string_with_max',
                'f.position_id' => 'nullable|integer_with_max',
                'f.status' => 'nullable|string_with_max',
            ];
    }
}
