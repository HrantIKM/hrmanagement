<?php

namespace App\Repositories\Review;

use App\Contracts\Review\IReviewRepository;
use App\Repositories\BaseRepository;
use App\Models\Review\Review;

class ReviewRepository extends BaseRepository implements IReviewRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }
}
