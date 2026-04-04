<?php

namespace App\Models\Task;

use App\Models\Base\BaseModel;
use App\Models\Project\Project;
use App\Models\Task\Enums\TaskPriority;
use App\Models\Task\Enums\TaskStatus;
use App\Models\Timesheet\Timesheet;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends BaseModel
{
    /**
     * @var string[]
     */
    protected $appends = [
        'priority_display',
        'status_display',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'project_id',
        'user_id',
    ];

    public array $defaultValues = [
        'priority' => TaskPriority::LOW,
        'status' => TaskStatus::TODO,
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    protected function priorityDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->priority
                ? __('task.priority.' . $this->priority)
                : ''
        );
    }

    protected function statusDisplay(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status
                ? __('task.status.' . $this->status)
                : ''
        );
    }
}
