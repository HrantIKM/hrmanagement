<?php

namespace App\Models\LeaveRequest;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
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
}
