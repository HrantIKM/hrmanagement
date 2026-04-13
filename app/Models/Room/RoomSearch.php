<?php

namespace App\Models\Room;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class RoomSearch extends Search
{
    protected array $orderables = [
        'id',
        'name'
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Room::select([
            'id',
            'name'
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'name'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['name']), function ($query) use ($filters) {
                $query->like('name', $filters['name']);
            });
    }

    public function totalCount(): int
    {
        return Room::count();
    }
}
