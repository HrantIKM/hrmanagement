<?php

namespace App\Models\Meeting;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class MeetingSearch extends Search
{
    protected array $orderables = [
        'id',
        'title',
        'status',
        'start_at',
        'end_at',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Meeting::query()
            ->with(['room:id,name'])
            ->select([
                'id',
                'title',
                'status',
                'room_id',
                'location',
                'start_at',
                'end_at',
            ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'title', 'description', 'location', 'summary'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['title']), function ($query) use ($filters) {
                $query->like('title', $filters['title']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            });
    }

    public function totalCount(): int
    {
        return Meeting::count();
    }
}
