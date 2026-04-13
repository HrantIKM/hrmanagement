<?php

namespace App\Models\LeaveRequest;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use App\Models\RoleAndPermission\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'user_id',
        'type',
        'status',
        'start_date',
        'end_date',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = LeaveRequest::with(['user:id,first_name,last_name'])
            ->select([
            'id',
            'user_id',
            'type',
            'status',
            'start_date',
            'end_date',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'reason'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(LeaveRequest::class);
    }

    public function setReturnData(Builder $query): mixed
    {
        $collection = $query->get();
        $isAdmin = auth()->user()?->hasRole(RoleType::ADMIN) ?? false;

        if ($isAdmin) {
            return $collection;
        }

        foreach ($collection as $leaveRequest) {
            $pending = $leaveRequest->status === Enums\LeaveRequestStatus::PENDING;
            $leaveRequest->setAttribute('canDelete', $pending);
            $leaveRequest->setAttribute('can_edit_leave_request', $pending);
        }

        return $collection;
    }
}
