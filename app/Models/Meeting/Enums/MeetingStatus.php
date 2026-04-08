<?php

namespace App\Models\Meeting\Enums;

final class MeetingStatus
{
    public const SCHEDULED = 'scheduled';

    public const IN_PROGRESS = 'in_progress';

    public const COMPLETED = 'completed';

    public const CANCELLED = 'cancelled';

    public const ALL = [
        self::SCHEDULED,
        self::IN_PROGRESS,
        self::COMPLETED,
        self::CANCELLED,
    ];
}
