<?php

namespace App\Repositories\Salary;

use App\Contracts\Salary\ISalaryRepository;
use App\Repositories\BaseRepository;
use App\Models\Salary\Salary;

class SalaryRepository extends BaseRepository implements ISalaryRepository
{
    public function __construct(Salary $model)
    {
        parent::__construct($model);
    }
}
