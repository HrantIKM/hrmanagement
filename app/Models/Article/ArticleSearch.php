<?php

namespace App\Models\Article;

use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class ArticleSearch extends Search
{
    protected array $orderables = [
        'id',
        'title',
        'publish_date',
        'description',
        'created_at',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        return Article::joinMl()->select([
            'id',
            'publish_date',
            'title',
            'description',
            'show_status',
            'created_at',
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
            ->when(!empty($filters['description']), function ($query) use ($filters) {
                $query->like('description', $filters['description']);
            })
            ->when(!empty($filters['show_status']), function ($query) use ($filters) {
                $query->where('show_status', $filters['show_status']);
            });
    }

    public function totalCount(): int
    {
        return Article::count();
    }
}
