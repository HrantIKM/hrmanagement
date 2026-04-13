<?php

namespace App\Models\Goal;

use App\Models\Base\BaseModel;
use App\Models\Goal\Enums\GoalType;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'type_display',
        'progress_percent',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'target_value',
        'current_value',
        'deadline',
        'type',
        'user_id',
    ];

    public array $defaultValues = [
        'type' => GoalType::QUANTITATIVE,
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'target_value' => 'decimal:2',
            'current_value' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function typeDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->type
                ? __('goal.type.' . $this->type)
                : ''
        );
    }

    /**
     * KPI-style completion vs target (null when target is missing or non-positive).
     */
    protected function progressPercent(): Attribute
    {
        return new Attribute(
            get: function () {
                $target = (float) ($this->target_value ?? 0);
                if ($target <= 0) {
                    return null;
                }
                $current = (float) ($this->current_value ?? 0);

                return min(100.0, round(($current / $target) * 100, 1));
            }
        );
    }
}
