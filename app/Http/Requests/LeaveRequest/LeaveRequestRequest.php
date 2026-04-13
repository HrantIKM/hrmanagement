<?php

namespace App\Http\Requests\LeaveRequest;

use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\RoleAndPermission\Enums\RoleType;
use App\Rules\NoLeaveOverlapRule;
use App\Rules\SickLeaveFutureDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequestRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $merge = [
            'approved_by' => $this->approved_by === '' ? null : $this->approved_by,
        ];

        $existing = $this->route('leave_request') ?? $this->route('leaveRequest');

        if (!auth()->user()->hasRole(RoleType::ADMIN)) {
            $merge['user_id'] = auth()->id();
            $merge['status'] = LeaveRequestStatus::PENDING;
            $merge['approved_by'] = null;
        } elseif ($existing) {
            $merge['user_id'] = $existing->user_id;
        } else {
            $merge['user_id'] = auth()->id();
        }

        $this->merge($merge);
    }

    public function rules(): array
    {
        $leaveRequestId = $this->route('leave_request')?->id ?? $this->route('leaveRequest')?->id;
        $startDate = (string) $this->input('start_date');
        $endDate = (string) $this->input('end_date');
        $userId = (int) $this->input('user_id');

        $rules = [
            'user_id' => 'required|integer_with_max|exist_validator:users,id',
            'type' => ['required', Rule::in(LeaveRequestType::ALL)],
            'start_date' => ['required', 'date', new SickLeaveFutureDateRule($this->input('type'))],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                new NoLeaveOverlapRule($userId, $startDate, $endDate, $leaveRequestId),
            ],
            'reason' => 'nullable|string',
        ];

        if (auth()->user()->hasRole(RoleType::ADMIN)) {
            $rules['status'] = ['required', Rule::in(LeaveRequestStatus::ALL)];
            $rules['approved_by'] = 'nullable|exist_validator:users,id';
        } else {
            $rules['status'] = ['required', Rule::in([LeaveRequestStatus::PENDING])];
        }

        return $rules;
    }
}
