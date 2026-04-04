<?php

namespace App\Models\Salary\Enums;

final class SalaryChangeReason
{
    public const PROMOTION = 'promotion';

    public const ANNUAL = 'annual';

    public const ADJUSTMENT = 'adjustment';

    public const ALL = [
        self::PROMOTION,
        self::ANNUAL,
        self::ADJUSTMENT,
    ];
}
