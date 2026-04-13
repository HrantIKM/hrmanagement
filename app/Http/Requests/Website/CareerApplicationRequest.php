<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CareerApplicationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'vacancy_id' => $this->vacancy_id === '' ? null : $this->vacancy_id,
            'email' => $this->email === '' ? null : $this->email,
        ]);
    }

    public function rules(): array
    {
        return [
            'vacancy_id' => 'required|exist_validator:vacancies,id',
            'full_name' => 'required|string_with_max',
            'email' => 'required|email|string_with_max',
            'resume' => 'required|file|mimes:pdf|max:10240',
        ];
    }
}
