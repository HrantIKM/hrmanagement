<?php

namespace App\Models\Department\Enums;

enum DepartmentCode: string
{
    case IT = 'IT';

    case HR = 'HR';

    case SALES = 'Sales';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
