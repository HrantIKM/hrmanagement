<?php

namespace App\Services\Goal;

use App\Contracts\Goal\IGoalRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Goal\Enums\GoalType;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class GoalService extends BaseService
{
    public function __construct(
        IGoalRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $data = parent::getViewData($id);
        $data['users'] = $this->userRepository->getForSelect();
        $data['goalTypeOptions'] = collect(GoalType::ALL)
            ->mapWithKeys(fn (string $v) => [$v => __('goal.type.' . $v)]);

        return $data;
    }

    public function getIndexViewData(): array
    {
        return [
            'users' => $this->userRepository->getForSelect(),
            'goalTypeOptions' => collect(GoalType::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('goal.type.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        foreach (['target_value', 'current_value', 'deadline'] as $key) {
            if (array_key_exists($key, $data) && $data[$key] === '') {
                $data[$key] = null;
            }
        }

        return parent::createOrUpdate($data, $id);
    }
}
