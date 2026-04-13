<?php

namespace App\Http\Requests\LeaveBalance;

use Illuminate\Foundation\Http\FormRequest;

class LeaveBalanceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'used_days' => $this->used_days === '' ? 0 : $this->used_days,
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exist_validator:users,id',
            'year' => 'required|integer|min:2000|max:2100',
            'total_days' => 'required|numeric|min:0|max:365',
            'used_days' => 'required|numeric|min:0|max:365',
        ];
    }
}
