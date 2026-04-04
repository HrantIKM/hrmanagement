<?php

namespace App\Models\Candidate;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class CandidateSearch extends Search
{
    protected array $orderables = [
        'id',
        'full_name',
        'email',
        'match_score',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Candidate::with(['vacancy', 'skills'])->select([
            'id',
            'full_name',
            'email',
            'resume_path',
            'match_score',
            'vacancy_id',
        ])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'full_name', 'email'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['full_name']), function ($query) use ($filters) {
                $query->like('full_name', $filters['full_name']);
            })
            ->when(!empty($filters['email']), function ($query) use ($filters) {
                $query->like('email', $filters['email']);
            })
            ->when(!empty($filters['vacancy_id']), function ($query) use ($filters) {
                $query->where('vacancy_id', $filters['vacancy_id']);
            });
    }

    public function totalCount(): int
    {
        return Candidate::count();
    }
}
