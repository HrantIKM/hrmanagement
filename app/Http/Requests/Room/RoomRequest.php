<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string_with_max'
        ];
    }
}
