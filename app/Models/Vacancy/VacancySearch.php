<?php

namespace App\Models\Vacancy;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class VacancySearch extends Search
{
    protected array $orderables = [
        'id',
        'title',
        'status',
        'closing_date',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Vacancy::with(['position', 'skills'])->select([
            'id',
            'title',
            'description',
            'status',
            'closing_date',
            'position_id',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'title', 'description'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['title']), function ($query) use ($filters) {
                $query->like('title', $filters['title']);
            })
            ->when(!empty($filters['position_id']), function ($query) use ($filters) {
                $query->where('position_id', $filters['position_id']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            });
    }

    public function totalCount(): int
    {
        return Vacancy::count();
    }
}
