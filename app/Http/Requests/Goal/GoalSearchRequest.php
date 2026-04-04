<?php

namespace App\Http\Requests\Goal;

use App\Http\Requests\Core\DatatableSearchRequest;
use App\Models\Goal\Enums\GoalType;
use Illuminate\Validation\Rule;

class GoalSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.id' => 'nullable|integer_with_max',
            'f.title' => 'nullable|string_with_max',
            'f.user_id' => 'nullable|integer_with_max|exists:users,id',
            'f.type' => ['nullable', Rule::in(GoalType::ALL)],
        ];
    }
}
