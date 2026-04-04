<?php

namespace App\Http\Requests\Vacancy;

use App\Models\Vacancy\Enums\VacancyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'position_id' => $this->position_id === '' ? null : $this->position_id,
            'closing_date' => $this->closing_date === '' ? null : $this->closing_date,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string_with_max',
            'description' => 'nullable|string_with_max',
            'status' => ['required', Rule::in(VacancyStatus::ALL)],
            'closing_date' => 'nullable|date',
            'position_id' => 'nullable|exist_validator:positions,id',

            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exist_validator:skills,id',
        ];
    }
}
