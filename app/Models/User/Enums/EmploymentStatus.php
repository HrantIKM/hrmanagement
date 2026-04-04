<?php

namespace App\Models\User\Enums;

final class EmploymentStatus
{
    public const ACTIVE = 'active';

    public const ON_LEAVE = 'on_leave';

    public const TERMINATED = 'terminated';

    public const ALL = [
        self::ACTIVE,
        self::ON_LEAVE,
        self::TERMINATED,
    ];
}
