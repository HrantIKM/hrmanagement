<?php

namespace App\Models\User;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class UserSearch extends Search
{
    protected array $orderables = [
        'id',
        'first_name',
        'last_name',
        'email',
        'created_at',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return User::with('roles')->select([
            'id',
            'first_name',
            'last_name',
            'email',
            'created_at',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'first_name', 'last_name', 'email'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['first_name']), function ($query) use ($filters) {
                $query->like('first_name', $filters['first_name']);
            })
            ->when(!empty($filters['last_name']), function ($query) use ($filters) {
                $query->like('last_name', $filters['last_name']);
            })
            ->when(!empty($filters['email']), function ($query) use ($filters) {
                $query->like('email', $filters['email']);
            })
            ->when(!empty($filters['created_at']), function ($query) use ($filters) {
                $query->orderBy('created_at', $filters['created_at']);
            });
    }

    public function totalCount(): int
    {
        return User::count();
    }
}
