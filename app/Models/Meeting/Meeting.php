<?php

namespace App\Models\Meeting;

use App\Models\Base\BaseModel;
use App\Models\Meeting\Enums\MeetingStatus;
use App\Models\Room\Room;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends BaseModel
{
    protected $appends = [
        'status_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'room_id',
        'location',
        'start_at',
        'end_at',
        'status',
        'summary',
    ];

    public array $defaultValues = [
        'status' => MeetingStatus::SCHEDULED,
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('meeting.status.' . $this->status)
                : ''
        );
    }
}
