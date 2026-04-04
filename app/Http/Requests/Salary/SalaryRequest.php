<?php

namespace App\Http\Requests\Salary;

use App\Models\Salary\Enums\SalaryChangeReason;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|double_with_max',
            'effective_date' => 'required|date_validator',
            'change_reason' => ['required', Rule::in(SalaryChangeReason::ALL)],
            'user_id' => 'required|integer_with_max|exists:users,id',
        ];
    }
}
