<?php

namespace App\Models\Department\Traits;

use App\Models\File\File;
use App\Models\Position\Position;
use App\Models\Skill\Skill;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait DepartmentRelations
{
    public function icon(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('field_name', 'icon');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
