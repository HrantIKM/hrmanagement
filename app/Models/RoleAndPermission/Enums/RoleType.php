<?php

namespace App\Models\RoleAndPermission\Enums;

final class RoleType
{
    public const ADMIN = 'admin';
    public const USER = 'user';

    public const ALL = [
        self::ADMIN,
        self::USER,
    ];
}
