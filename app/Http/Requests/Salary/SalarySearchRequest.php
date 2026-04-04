<?php

namespace App\Http\Requests\Salary;

use App\Http\Requests\Core\DatatableSearchRequest;
use App\Models\Salary\Enums\SalaryChangeReason;
use Illuminate\Validation\Rule;

class SalarySearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.id' => 'nullable|integer_with_max',
            'f.user_id' => 'nullable|integer_with_max|exists:users,id',
            'f.change_reason' => ['nullable', Rule::in(SalaryChangeReason::ALL)],
        ];
    }
}
