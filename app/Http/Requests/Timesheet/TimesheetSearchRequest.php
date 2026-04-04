<?php

namespace App\Http\Requests\Timesheet;

use App\Http\Requests\Core\DatatableSearchRequest;

class TimesheetSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.user_id' => 'nullable|integer_with_max',
                'f.task_id' => 'nullable|integer_with_max',
                'f.date' => 'nullable|date',
            ];
    }
}
