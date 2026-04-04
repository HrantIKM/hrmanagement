<?php

namespace App\Models\Project\Traits;

use App\Models\File\File;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait ProjectRelations
{
    public function icon(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('field_name', 'icon');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
