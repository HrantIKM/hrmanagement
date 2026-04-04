<?php

namespace App\Http\Requests\Goal;

use App\Models\Goal\Enums\GoalType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string_with_max',
            'target_value' => 'nullable|numeric|between:0,999999.99',
            'current_value' => 'nullable|numeric|between:0,999999.99',
            'deadline' => 'nullable|date_validator',
            'type' => ['required', Rule::in(GoalType::ALL)],
            'user_id' => 'required|integer_with_max|exists:users,id',
        ];
    }
}
