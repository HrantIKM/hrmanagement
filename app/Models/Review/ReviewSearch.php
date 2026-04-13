<?php

namespace App\Models\Review;

use App\Models\Base\Concerns\ScopesEmployeeOwnedDataTable;
use App\Models\Base\Search;
use Illuminate\Database\Eloquent\Builder;

class ReviewSearch extends Search
{
    use ScopesEmployeeOwnedDataTable;

    protected array $orderables = [
        'id',
        'rating',
        'review_period',
    ];

    protected function query(): Builder
    {
        $filters = $this->filters;

        $query = Review::with(['user', 'reviewer'])->select([
            'id',
            'rating',
            'feedback_text',
            'review_period',
            'review_perspective',
            'user_id',
            'reviewer_id',
            'created_at',
        ]);

        $this->scopeToAssigneeUnlessAdmin($query);

        return $query
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->likeOr(['id', 'feedback_text'], $filters);
            })
            ->when(!empty($filters['id']), function ($query) use ($filters) {
                $query->where('id', $filters['id']);
            })
            ->when(!empty($filters['user_id']) && $this->dashboardUserIsAdmin(), function ($query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            })
            ->when(!empty($filters['reviewer_id']), function ($query) use ($filters) {
                $query->where('reviewer_id', $filters['reviewer_id']);
            })
            ->when(!empty($filters['review_period']), function ($query) use ($filters) {
                $query->where('review_period', $filters['review_period']);
            })
            ->when(!empty($filters['review_perspective']), function ($query) use ($filters) {
                $query->where('review_perspective', $filters['review_perspective']);
            });
    }

    public function totalCount(): int
    {
        return $this->assigneeScopedTotalCount(Review::class);
    }
}
