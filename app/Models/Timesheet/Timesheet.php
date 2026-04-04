<?php

namespace App\Models\Timesheet;

use App\Models\Base\BaseModel;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
