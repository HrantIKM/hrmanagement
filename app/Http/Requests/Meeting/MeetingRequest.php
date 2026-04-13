<?php

namespace App\Http\Requests\Meeting;

use App\Models\Meeting\Enums\MeetingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MeetingRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => $this->description === '' ? null : $this->description,
            'room_id' => $this->room_id === '' ? null : $this->room_id,
            'location' => $this->location === '' ? null : $this->location,
            'summary' => $this->summary === '' ? null : $this->summary,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string_with_max',
            'description' => 'nullable|string_with_max',
            'room_id' => 'nullable|exist_validator:rooms,id',
            'location' => 'nullable|string_with_max',
            'start_at' => 'required|date',
            'end_at' => [
                'required',
                'date',
                'after:start_at',
            ],
            'status' => ['required', Rule::in(MeetingStatus::ALL)],
            'summary' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exist_validator:users,id',
        ];
    }
}
