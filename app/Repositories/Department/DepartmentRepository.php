<?php

namespace App\Repositories\Department;

use App\Contracts\Department\IDepartmentRepository;
use App\Repositories\BaseRepository;
use App\Models\Department\Department;

class DepartmentRepository extends BaseRepository implements IDepartmentRepository
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }
}
