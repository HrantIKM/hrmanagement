<?php

namespace App\Models\LeaveRequest\Enums;

final class LeaveRequestType
{
    public const VACATION = 'vacation';

    public const SICK_LEAVE = 'sick_leave';

    public const DAY_OFF = 'day_off';

    public const ALL = [
        self::VACATION,
        self::SICK_LEAVE,
        self::DAY_OFF,
    ];
}
