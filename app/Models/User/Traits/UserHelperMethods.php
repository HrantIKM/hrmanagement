<?php

namespace App\Models\User\Traits;

use App\Models\RoleAndPermission\Role;

trait UserHelperMethods
{
    public function syncRolesData(array $roleIds): void
    {
        $roles = Role::getRoleNames($roleIds);
        $this->syncRoles($roles);
    }
}
