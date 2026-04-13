<?php

namespace App\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string_with_max',
            'date' => 'required|date',
            'is_public' => 'nullable|boolean',
        ];
    }
}
