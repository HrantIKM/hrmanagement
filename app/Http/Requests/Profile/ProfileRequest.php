<?php

namespace App\Http\Requests\Profile;

use App\Rules\Password\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string_with_max',
            'last_name' => 'required|string_with_max',
            'avatar' => 'nullable|string_with_max',

            'current_password' => ['nullable', 'string_with_max', 'required_with:new_password', new MatchOldPassword()],
            'new_password' => [
                'nullable',
                'required_with:current_password',
                'confirmed',
                'max:16',
                Password::min(6)
//                    ->mixedCase()
                    ->letters()
                    ->numbers(),
                //                    ->symbols()
                //                    ->uncompromised()
            ],
        ];
    }
}
