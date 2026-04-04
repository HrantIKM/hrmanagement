<?php

namespace App\Services\Salary;

use App\Contracts\Salary\ISalaryRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Salary\Enums\SalaryChangeReason;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class SalaryService extends BaseService
{
    public function __construct(
        ISalaryRepository $repository,
        protected IUserRepository $userRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $data = parent::getViewData($id);
        $data['users'] = $this->userRepository->getForSelect();
        $data['salaryChangeReasonOptions'] = collect(SalaryChangeReason::ALL)
            ->mapWithKeys(fn (string $v) => [$v => __('salary.change_reason.' . $v)]);

        return $data;
    }

    public function getIndexViewData(): array
    {
        return [
            'users' => $this->userRepository->getForSelect(),
            'salaryChangeReasonOptions' => collect(SalaryChangeReason::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('salary.change_reason.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        if (array_key_exists('effective_date', $data) && $data['effective_date'] === '') {
            $data['effective_date'] = null;
        }

        return parent::createOrUpdate($data, $id);
    }
}
