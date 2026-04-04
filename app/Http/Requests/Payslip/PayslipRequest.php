<?php

namespace App\Http\Requests\Payslip;

use Illuminate\Foundation\Http\FormRequest;

class PayslipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|between:2000,2100',
            'base_amount' => 'required|numeric|between:0,999999.99',
            'bonus' => 'nullable|numeric|between:0,999999.99',
            'deductions' => 'nullable|numeric|between:0,999999.99',
            'net_total' => 'required|numeric|between:0,999999.99',
            'user_id' => 'required|integer_with_max|exists:users,id',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }
}
