<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
                Rule::notIn([(int) auth()->id()]),
            ],
            'body' => ['required', 'string', 'min:1', 'max:10000'],
        ];
    }
}
