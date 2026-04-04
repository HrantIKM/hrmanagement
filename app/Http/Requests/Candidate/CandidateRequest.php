<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'vacancy_id' => $this->vacancy_id === '' ? null : $this->vacancy_id,
            'match_score' => $this->match_score === '' ? null : $this->match_score,
            'email' => $this->email === '' ? null : $this->email,
            'resume_path' => $this->resume_path === '' ? null : $this->resume_path,
            'raw_ai_data' => $this->raw_ai_data === '' ? null : $this->raw_ai_data,
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string_with_max',
            'email' => 'nullable|email|string_with_max',
            'resume_path' => 'nullable|string|max:512',
            'raw_ai_data' => 'nullable|json',
            'match_score' => 'nullable|integer|min:0',
            'vacancy_id' => 'nullable|exist_validator:vacancies,id',

            'skill_ids' => 'nullable|array',
            'skill_ids.*' => 'exist_validator:skills,id',
        ];
    }
}
