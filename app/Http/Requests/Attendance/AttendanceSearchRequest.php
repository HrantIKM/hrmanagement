<?php

namespace App\Http\Requests\Attendance;

use App\Http\Requests\Core\DatatableSearchRequest;

class AttendanceSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.user_id' => 'nullable|integer_with_max',
                'f.status' => 'nullable|string_with_max',
                'f.date' => 'nullable|date',
            ];
    }
}
