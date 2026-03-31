<?php

namespace App\Models\User\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait UserAccessors
{
    public function name(): Attribute
    {
        return new Attribute(
            get: fn() => $this->first_name . ' ' . $this->last_name
        );
    }
}
