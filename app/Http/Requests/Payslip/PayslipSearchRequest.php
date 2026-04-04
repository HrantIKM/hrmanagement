<?php

namespace App\Http\Requests\Payslip;

use App\Http\Requests\Core\DatatableSearchRequest;

class PayslipSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.id' => 'nullable|integer_with_max',
            'f.user_id' => 'nullable|integer_with_max|exists:users,id',
            'f.period_month' => 'nullable|integer|between:1,12',
            'f.period_year' => 'nullable|integer|between:2000,2100',
        ];
    }
}
