<?php

namespace App\Services\Department;

use App\Contracts\Department\IDepartmentRepository;
use App\Services\BaseService;

class DepartmentService extends BaseService
{
    public function __construct(
        IDepartmentRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        if ($id === null) {
            return [
                'department' => $this->repository->getInstance(),
            ];
        }

        return [
            'department' => $this->repository->find($id, ['icon']),
        ];
    }
}
