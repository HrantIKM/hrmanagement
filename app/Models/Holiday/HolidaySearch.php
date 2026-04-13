<?php

namespace App\Models\Holiday;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class HolidaySearch extends Search
{
    protected array $orderables = [
        'id',
        'name',
        'date',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Holiday::select([
            'id',
            'name',
            'date',
            'is_public',
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
            ->when(!empty($filters['date']), function ($query) use ($filters) {
                $query->whereDate('date', $filters['date']);
            });
    }

    public function totalCount(): int
    {
        return Holiday::count();
    }
}
