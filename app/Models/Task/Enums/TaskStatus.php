<?php

namespace App\Models\Task\Enums;

final class TaskStatus
{
    public const TODO = 'todo';

    public const IN_PROGRESS = 'in_progress';

    public const REVIEW = 'review';

    public const DONE = 'done';

    public const ALL = [
        self::TODO,
        self::IN_PROGRESS,
        self::REVIEW,
        self::DONE,
    ];
}
