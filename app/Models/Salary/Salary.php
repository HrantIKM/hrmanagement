<?php

namespace App\Models\Salary;

use App\Models\Base\BaseModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'change_reason_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'amount',
        'effective_date',
        'change_reason',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function changeReasonDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->change_reason
                ? __('salary.change_reason.' . $this->change_reason)
                : ''
        );
    }
}
