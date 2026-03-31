<?php

namespace App\Services\Article;

use App\Contracts\Article\IArticleRepository;
use App\Contracts\User\IUserRepository;
use App\Services\BaseService;

class ArticleService extends BaseService
{
    public function __construct(
        IArticleRepository $repository,
        private readonly IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $viewData = parent::getViewData($id);

        return $viewData + [
            'users' => $this->userRepository->getForSelect(),
        ];
    }

    /* public function createOrUpdate(array $data, int $id = null): Model
     {
         return DB::transaction(function () use ($data, $id) {
             $article = parent::createOrUpdateWithoutTransaction($data, $id);

             //

             return $article;
         });
     }*/
}
