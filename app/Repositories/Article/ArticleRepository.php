<?php

namespace App\Repositories\Article;

use App\Contracts\Article\IArticleRepository;
use App\Models\Article\Article;
use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository implements IArticleRepository
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }
}
