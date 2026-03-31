<?php

namespace App\Models\Base\Enums;

final class ShowStatus
{
    public const ACTIVE = '1';
    public const INACTIVE = '2';
    public const DELETED = '0';

    public const ALL = [
        self::ACTIVE,
        self::INACTIVE,
        self::DELETED,
    ];

    public const FOR_SELECT = [
        self::ACTIVE,
        self::INACTIVE,
    ];
}
