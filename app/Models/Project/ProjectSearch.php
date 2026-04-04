<?php

namespace App\Models\Project;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class ProjectSearch extends Search
{
    protected array $orderables = [
        'id',
        'name',
        'status',
        'start_date',
        'end_date',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Project::select([
            'id',
            'name',
            'description',
            'start_date',
            'end_date',
            'status',
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
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            });
    }

    public function totalCount(): int
    {
        return Project::count();
    }
}
