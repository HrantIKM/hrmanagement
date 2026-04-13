<?php

namespace App\Rules;

use App\Models\LeaveRequest\Enums\LeaveRequestType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SickLeaveFutureDateRule implements ValidationRule
{
    public function __construct(
        private readonly ?string $type
    ) {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->type !== LeaveRequestType::SICK_LEAVE) {
            return;
        }

        if (!$value) {
            return;
        }

        if (strtotime((string) $value) > strtotime(date('Y-m-d'))) {
            $fail('Sick leave cannot start in the future.');
        }
    }
}
