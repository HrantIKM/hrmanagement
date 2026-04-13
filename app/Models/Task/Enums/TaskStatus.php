<?php

namespace App\Models\Task\Enums;

final class TaskStatus
{
    public const BACKLOG = 'backlog';

    public const TODO = 'todo';

    public const IN_PROGRESS = 'in_progress';

    public const READY_TO_TEST = 'ready_to_test';

    public const REVIEW = 'review';

    public const DONE = 'done';

    public const ALL = [
        self::BACKLOG,
        self::TODO,
        self::IN_PROGRESS,
        self::READY_TO_TEST,
        self::DONE,
    ];
}
