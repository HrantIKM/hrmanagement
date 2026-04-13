<?php

namespace App\Models\Holiday;

use App\Models\Base\BaseModel;

class Holiday extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'date',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_public' => 'boolean',
        ];
    }
}
