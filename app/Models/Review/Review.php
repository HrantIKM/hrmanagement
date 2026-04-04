<?php

namespace App\Models\Review;

use App\Models\Base\BaseModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'review_period_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'rating',
        'feedback_text',
        'review_period',
        'user_id',
        'reviewer_id',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    protected function reviewPeriodDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->review_period
                ? __('review.period.' . $this->review_period)
                : ''
        );
    }
}
