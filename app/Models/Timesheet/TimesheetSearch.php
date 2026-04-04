<?php

namespace App\Models\Timesheet;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class TimesheetSearch extends Search
{
    protected array $orderables = [
        'id',
        'date',
        'duration_minutes',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Timesheet::with(['user', 'task'])->select([
            'id',
            'user_id',
            'task_id',
            'date',
            'start_time',
            'end_time',
            'duration_minutes',
            'note',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'note'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['task_id']), function ($query) use ($filters) {
                $query->where('task_id', $filters['task_id']);
            })
            ->when(!empty($filters['date']), function ($query) use ($filters) {
                $query->whereDate('date', $filters['date']);
            });
    }

    public function totalCount(): int
    {
        return Timesheet::count();
    }
}
