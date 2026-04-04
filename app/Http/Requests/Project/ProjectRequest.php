<?php

namespace App\Http\Requests\Project;

use App\Models\Project\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_date' => $this->start_date === '' ? null : $this->start_date,
            'end_date' => $this->end_date === '' ? null : $this->end_date,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string_with_max',
            'description' => 'nullable|string_with_max',
            'start_date' => 'nullable|date',
            'end_date' => [
                'nullable',
                'date',
                Rule::when($this->filled('start_date'), ['after_or_equal:start_date']),
            ],
            'status' => ['required', Rule::in(ProjectStatus::ALL)],
            'icon' => 'required|string_with_max',

            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exist_validator:users,id',
        ];
    }
}
