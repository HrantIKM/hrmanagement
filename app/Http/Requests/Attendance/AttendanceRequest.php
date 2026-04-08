<?php

namespace App\Http\Requests\Attendance;

use App\Models\Attendance\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exist_validator:users,id',
            'date' => 'required|date',
            'clock_in' => 'required|date',
            'clock_out' => 'nullable|date|after:clock_in',
            'status' => ['required', Rule::in(AttendanceStatus::ALL)],
        ];
    }
}
