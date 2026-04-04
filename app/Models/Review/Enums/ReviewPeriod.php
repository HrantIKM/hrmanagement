<?php

namespace App\Models\Review\Enums;

final class ReviewPeriod
{
    public const Q1 = 'q1';

    public const Q2 = 'q2';

    public const Q3 = 'q3';

    public const Q4 = 'q4';

    public const ALL = [
        self::Q1,
        self::Q2,
        self::Q3,
        self::Q4,
    ];
}
