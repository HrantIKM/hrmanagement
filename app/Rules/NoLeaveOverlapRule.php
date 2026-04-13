<?php

namespace App\Rules;

use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\LeaveRequest;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoLeaveOverlapRule implements ValidationRule
{
    public function __construct(
        private readonly int $userId,
        private readonly string $startDate,
        private readonly string $endDate,
        private readonly ?int $ignoreId = null
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = LeaveRequest::query()
            ->where('user_id', $this->userId)
            ->where('status', LeaveRequestStatus::APPROVED)
            ->when($this->ignoreId, fn ($q) => $q->where('id', '!=', $this->ignoreId))
            ->where(function ($q) {
                $q->where('start_date', '<=', $this->endDate)
                    ->where('end_date', '>=', $this->startDate);
            })
            ->exists();

        if ($exists) {
            $fail('This leave overlaps with an already approved leave.');
        }
    }
}
