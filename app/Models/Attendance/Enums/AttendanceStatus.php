<?php

namespace App\Models\Attendance\Enums;

final class AttendanceStatus
{
    public const PRESENT = 'present';

    public const LATE = 'late';

    public const ABSENT = 'absent';

    public const ALL = [
        self::PRESENT,
        self::LATE,
        self::ABSENT,
    ];
}
