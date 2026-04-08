<?php

namespace App\Models\Base\Concerns;

use App\Models\RoleAndPermission\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

trait ScopesEmployeeOwnedDataTable
{
    protected function dashboardUserIsAdmin(): bool
    {
        return auth()->user()?->hasRole(RoleType::ADMIN) ?? false;
    }

    protected function scopeToAssigneeUnlessAdmin(Builder $query, string $userIdColumn = 'user_id'): Builder
    {
        if (!$this->dashboardUserIsAdmin()) {
            $query->where($userIdColumn, auth()->id());
        }

        return $query;
    }

    protected function scopeToProjectMemberUnlessAdmin(Builder $query): Builder
    {
        if (!$this->dashboardUserIsAdmin()) {
            $query->whereHas('users', fn($q) => $q->where('users.id', auth()->id()));
        }

        return $query;
    }

    protected function assigneeScopedTotalCount(string $modelClass, string $userIdColumn = 'user_id'): int
    {
        $query = $modelClass::query();

        return $this->scopeToAssigneeUnlessAdmin($query, $userIdColumn)->count();
    }
}
