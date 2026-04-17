<?php

namespace App\Models\Salary;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class SalarySearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'amount',
        'effective_date',
        'change_reason',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = Salary::with(['user'])->select([
            'id',
            'amount',
            'effective_date',
            'change_reason',
            'user_id',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']) && $this->dashboardUserIsAdmin(), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['change_reason']), function ($query) use ($filters) {
                $query->where('change_reason', $filters['change_reason']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(Salary::class);
    }
}
