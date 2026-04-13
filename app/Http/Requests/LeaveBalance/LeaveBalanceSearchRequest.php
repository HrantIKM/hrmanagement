<?php

namespace App\Http\Requests\LeaveBalance;

use App\Http\Requests\Core\DatatableSearchRequest;

class LeaveBalanceSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.user_id' => 'nullable|integer_with_max',
                'f.year' => 'nullable|integer_with_max',
            ];
    }
}
