<?php

namespace App\Repositories\Payslip;

use App\Contracts\Payslip\IPayslipRepository;
use App\Repositories\BaseRepository;
use App\Models\Payslip\Payslip;

class PayslipRepository extends BaseRepository implements IPayslipRepository
{
    public function __construct(Payslip $model)
    {
        parent::__construct($model);
    }
}
