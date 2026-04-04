<?php

namespace App\Models\Position;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class PositionSearch extends Search
{
    protected array $orderables = [
        'id',
        'title',
        'min_salary',
        'max_salary',
        'grade_level',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Position::with('department')->select([
            'id',
            'title',
            'min_salary',
            'max_salary',
            'grade_level',
            'department_id',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'title', 'grade_level'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['title']), function ($query) use ($filters) {
                $query->like('title', $filters['title']);
            })
            ->when(!empty($filters['department_id']), function ($query) use ($filters) {
                $query->where('department_id', $filters['department_id']);
            });
    }

    public function totalCount(): int
    {
        return Position::count();
    }
}
