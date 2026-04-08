<?php

namespace App\Http\Requests\LeaveRequest;

use App\Http\Requests\Core\DatatableSearchRequest;

class LeaveRequestSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.user_id' => 'nullable|integer_with_max',
                'f.type' => 'nullable|string_with_max',
                'f.status' => 'nullable|string_with_max',
            ];
    }
}
