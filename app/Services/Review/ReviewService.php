<?php

namespace App\Services\Review;

use App\Contracts\Review\IReviewRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Review\Enums\ReviewPeriod;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class ReviewService extends BaseService
{
    public function __construct(
        IReviewRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $data = parent::getViewData($id);
        $data['users'] = $this->userRepository->getForSelect();
        $data['reviewPeriodOptions'] = collect(ReviewPeriod::ALL)
            ->mapWithKeys(fn (string $v) => [$v => __('review.period.' . $v)]);

        return $data;
    }

    public function getIndexViewData(): array
    {
        return [
            'users' => $this->userRepository->getForSelect(),
            'reviewPeriodOptions' => collect(ReviewPeriod::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('review.period.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        if (array_key_exists('feedback_text', $data) && $data['feedback_text'] === '') {
            $data['feedback_text'] = null;
        }

        return parent::createOrUpdate($data, $id);
    }
}
