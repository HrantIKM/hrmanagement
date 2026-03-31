<?php

namespace App\Models\User\Traits;

use App\Models\File\File;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait UserRelations
{
    public function avatar(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->where('field_name', 'avatar');
    }
}
