<?php

namespace App\Models\Vacancy\Enums;

final class VacancyStatus
{
    public const OPEN = 'open';

    public const CLOSED = 'closed';

    public const ALL = [
        self::OPEN,
        self::CLOSED,
    ];
}
