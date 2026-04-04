<?php

namespace App\Models\Goal\Enums;

final class GoalType
{
    public const QUANTITATIVE = 'quantitative';

    public const QUALITATIVE = 'qualitative';

    public const ALL = [
        self::QUANTITATIVE,
        self::QUALITATIVE,
    ];
}
