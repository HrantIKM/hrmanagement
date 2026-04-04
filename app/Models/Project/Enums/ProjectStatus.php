<?php

namespace App\Models\Project\Enums;

final class ProjectStatus
{
    public const PLANNING = 'planning';

    public const ACTIVE = 'active';

    public const COMPLETED = 'completed';

    public const ALL = [
        self::PLANNING,
        self::ACTIVE,
        self::COMPLETED,
    ];
}
