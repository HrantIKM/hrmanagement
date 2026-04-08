<?php

namespace App\Models\Attendance;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class AttendanceSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'user_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'total_hours',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = Attendance::with(['user:id,first_name,last_name'])
            ->select([
            'id',
            'user_id',
            'date',
            'clock_in',
            'clock_out',
            'total_hours',
            'status',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['date']), function ($query) use ($filters) {
                $query->whereDate('date', $filters['date']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(Attendance::class);
    }
}
