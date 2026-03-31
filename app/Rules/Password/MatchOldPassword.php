<?php

namespace App\Rules\Password;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MatchOldPassword implements Rule
{
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return Hash::check($value, auth()->user()->password);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The field value is not match with current password.';
    }
}
