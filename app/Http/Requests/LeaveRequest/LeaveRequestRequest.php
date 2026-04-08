<?php

namespace App\Http\Requests\LeaveRequest;

use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exist_validator:users,id',
            'type' => ['required', Rule::in(LeaveRequestType::ALL)],
            'status' => ['required', Rule::in(LeaveRequestStatus::ALL)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'approved_by' => 'nullable|exist_validator:users,id',
        ];
    }
}
