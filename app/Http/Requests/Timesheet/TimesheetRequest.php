<?php

namespace App\Http\Requests\Timesheet;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'task_id' => $this->task_id === '' ? null : $this->task_id,
            'start_time' => $this->start_time === '' ? null : $this->start_time,
            'end_time' => $this->end_time === '' ? null : $this->end_time,
            'duration_minutes' => $this->duration_minutes === '' ? null : $this->duration_minutes,
            'note' => $this->note === '' ? null : $this->note,
            'date' => $this->date === '' ? null : $this->date,
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exist_validator:users,id',
            'task_id' => 'nullable|exist_validator:tasks,id',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:0',
            'note' => 'nullable|string_with_max',
        ];
    }
}
