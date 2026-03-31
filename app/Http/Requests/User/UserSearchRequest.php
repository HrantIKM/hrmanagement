<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Core\DatatableSearchRequest;

class UserSearchRequest extends DatatableSearchRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'f.first_name' => 'nullable|string_with_max',
            'f.last_name' => 'nullable|string_with_max',
            'f.email' => 'nullable|string_with_max',
        ];
    }
}
