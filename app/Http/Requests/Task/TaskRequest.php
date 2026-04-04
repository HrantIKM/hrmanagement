<?php

namespace App\Http\Requests\Task;

use App\Models\Task\Enums\TaskPriority;
use App\Models\Task\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'project_id' => $this->project_id === '' ? null : $this->project_id,
            'user_id' => $this->user_id === '' ? null : $this->user_id,
            'due_date' => $this->due_date === '' ? null : $this->due_date,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string_with_max',
            'description' => 'nullable|string_with_max',
            'priority' => ['required', Rule::in(TaskPriority::ALL)],
            'status' => ['required', Rule::in(TaskStatus::ALL)],
            'due_date' => 'nullable|date',
            'project_id' => 'nullable|exist_validator:projects,id',
            'user_id' => 'nullable|exist_validator:users,id',
        ];
    }
}
