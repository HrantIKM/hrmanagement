<?php

namespace App\Models\Skill;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class SkillSearch extends Search
{
    protected array $orderables = [
        'id',
        'name',
        'category',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Skill::with(['department'])->select([
            'id',
            'name',
            'category',
            'department_id',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'name'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['name']), function ($query) use ($filters) {
                $query->like('name', $filters['name']);
            })
            ->when(!empty($filters['category']), function ($query) use ($filters) {
                $query->where('category', $filters['category']);
            })
            ->when(!empty($filters['department_id']), function ($query) use ($filters) {
                $query->where('department_id', $filters['department_id']);
            });
    }

    public function totalCount(): int
    {
        return Skill::count();
    }
}
