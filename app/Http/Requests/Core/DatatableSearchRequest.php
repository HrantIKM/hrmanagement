<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class DatatableSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start' => 'required|integer_with_max',
            'perPage' => 'required|integer_with_max',
            'f.search' => 'nullable|string_with_max',

            'order.sort_by' => 'nullable|string_with_max',
            'order.sort_desc' => 'nullable|in:asc,desc|string_with_max',
        ];
    }
}
