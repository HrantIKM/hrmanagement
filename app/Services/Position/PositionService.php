<?php

namespace App\Services\Position;

use App\Contracts\Department\IDepartmentRepository;
use App\Contracts\Position\IPositionRepository;
use App\Services\BaseService;

class PositionService extends BaseService
{
    public function __construct(
        IPositionRepository $repository,
        protected IDepartmentRepository $departmentRepository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $data = parent::getViewData($id);
        $data['departments'] = $this->departmentRepository->getForSelect();

        return $data;
    }
}
