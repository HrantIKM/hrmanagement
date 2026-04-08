<?php

namespace App\Models\Goal;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class GoalSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'title',
        'deadline',
        'type',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = Goal::with(['user'])->select([
            'id',
            'title',
            'target_value',
            'current_value',
            'deadline',
            'type',
            'user_id',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'title'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['title']), function ($query) use ($filters) {
                $query->like('title', $filters['title']);
            })
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(Goal::class);
    }
}
