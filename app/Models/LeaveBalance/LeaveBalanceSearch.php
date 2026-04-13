<?php

namespace App\Models\LeaveBalance;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class LeaveBalanceSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'user_id',
        'year',
        'total_days',
        'used_days',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = LeaveBalance::with('user:id,first_name,last_name,email')->select([
            'id',
            'user_id',
            'year',
            'total_days',
            'used_days',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['year']), function ($query) use ($filters) {
                $query->where('year', $filters['year']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(LeaveBalance::class);
    }
}
