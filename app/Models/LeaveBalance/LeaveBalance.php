<?php

namespace App\Models\LeaveBalance;

use App\Models\Base\BaseModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'year',
        'total_days',
        'used_days',
    ];

    protected $appends = [
        'remaining_days',
    ];

    protected function casts(): array
    {
        return [
            'total_days' => 'decimal:2',
            'used_days' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingDaysAttribute(): float
    {
        return round((float) $this->total_days - (float) $this->used_days, 2);
    }
}
