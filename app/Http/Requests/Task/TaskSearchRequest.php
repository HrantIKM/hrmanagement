<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\Core\DatatableSearchRequest;

class TaskSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                'f.id' => 'nullable|integer_with_max',
                'f.title' => 'nullable|string_with_max',
                'f.project_id' => 'nullable|integer_with_max',
                'f.user_id' => 'nullable|integer_with_max',
                'f.priority' => 'nullable|string_with_max',
                'f.status' => 'nullable|string_with_max',
            ];
    }
}
