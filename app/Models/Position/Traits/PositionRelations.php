<?php

namespace App\Models\Position\Traits;

use App\Models\User\User;
use App\Models\Vacancy\Vacancy;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait PositionRelations
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }
}
