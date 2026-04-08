<?php

namespace App\Models\LeaveRequest;

use App\Models\Base\BaseModel;
use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends BaseModel
{
    protected $appends = [
        'type_display',
        'status_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'start_date',
        'end_date',
        'reason',
        'approved_by',
    ];

    public array $defaultValues = [
        'type' => LeaveRequestType::VACATION,
        'status' => LeaveRequestStatus::PENDING,
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected function typeDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->type
                ? __('leaveRequest.type.' . $this->type)
                : ''
        );
    }

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('leaveRequest.status.' . $this->status)
                : ''
        );
    }
}
