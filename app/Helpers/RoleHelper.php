<?php

use App\Models\RoleAndPermission\Enums\RoleType;
use App\Models\RoleAndPermission\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('getRoles')) {
    function getRoles(): array
    {
        $roles = [];
        foreach (RoleType::ALL as $role) {
            $roles[Str::upper($role)] = $role;
        }

        return $roles;
    }
}

if (!function_exists('getAuthUserRolesName')) {
    function getRolesIdName(): array
    {
        $roles = [];
        foreach (Role::all() as $role) {
            $roles[Str::upper($role->name)] = (string) $role->id;
        }

        return $roles;
    }
}

if (!function_exists('getAuthUserRolesName')) {
    function getAuthUserRolesName(): array
    {
        return Auth::user()->roles->pluck('name')->all();
    }
}
