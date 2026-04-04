<?php

namespace App\Http\Requests\Candidate;

use App\Http\Requests\Core\DatatableSearchRequest;

class CandidateSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.full_name' => 'nullable|string_with_max',
                'f.email' => 'nullable|string_with_max',
                'f.vacancy_id' => 'nullable|integer_with_max',
            ];
    }
}
