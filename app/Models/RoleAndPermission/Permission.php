<?php

namespace App\Models\RoleAndPermission;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'guard_name'];
}
