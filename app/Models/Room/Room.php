<?php

namespace App\Models\Room;

use App\Models\Base\BaseModel;
use App\Models\Meeting\Meeting;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}
