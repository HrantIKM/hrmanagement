<?php

namespace App\Models\Attendance;

use App\Models\Base\BaseModel;
use App\Models\Attendance\Enums\AttendanceStatus;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends BaseModel
{
    protected $appends = [
        'status_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'total_hours',
        'status',
    ];

    public array $defaultValues = [
        'status' => AttendanceStatus::PRESENT,
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'total_hours' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('attendance.status.' . $this->status)
                : ''
        );
    }
}
