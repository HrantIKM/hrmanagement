<?php

namespace App\Models\Department;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class DepartmentSearch extends Search
{
    protected array $orderables = [
        'id',
        'name',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Department::select([
            'id',
            'name',
            'description',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'name', 'description'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['name']), function ($query) use ($filters) {
                $query->like('name', $filters['name']);
            })
            ->when(!empty($filters['description']), function ($query) use ($filters) {
                $query->like('description', $filters['description']);
            });
    }

    public function totalCount(): int
    {
        return Department::count();
    }
}
