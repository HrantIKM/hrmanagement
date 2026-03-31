<?php

namespace App\Models\RoleAndPermission;

use App\Models\RoleAndPermission\Enums\RoleType;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name'];

    public static function getRolesFormatted(): string
    {
        return implode('|', RoleType::ALL);
    }

    public static function getRoleNames(array $ids): array
    {
        return Role::whereIn('id', $ids)->pluck('name')->all();
    }
}
