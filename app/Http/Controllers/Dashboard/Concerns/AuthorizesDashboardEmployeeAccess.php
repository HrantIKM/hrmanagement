<?php

namespace App\Http\Controllers\Dashboard\Concerns;

use App\Models\Project\Project;
use App\Models\RoleAndPermission\Enums\RoleType;

trait AuthorizesDashboardEmployeeAccess
{
    protected function dashboardUserIsAdmin(): bool
    {
        return auth()->user()->hasRole(RoleType::ADMIN);
    }

    protected function abortUnlessAdminCanManageHrRecords(): void
    {
        abort_unless($this->dashboardUserIsAdmin(), 403);
    }

    protected function abortUnlessAdminOrOwnsUserId(?int $userId): void
    {
        abort_unless(
            $this->dashboardUserIsAdmin() || (int) $userId === (int) auth()->id(),
            403
        );
    }

    protected function abortUnlessAdminOrProjectMember(Project $project): void
    {
        if ($this->dashboardUserIsAdmin()) {
            return;
        }

        abort_unless(
            $project->users()->where('users.id', auth()->id())->exists(),
            403
        );
    }
}
