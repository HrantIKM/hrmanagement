<?php

namespace App\Repositories\LeaveBalance;

use App\Contracts\LeaveBalance\ILeaveBalanceRepository;
use App\Repositories\BaseRepository;
use App\Models\LeaveBalance\LeaveBalance;

class LeaveBalanceRepository extends BaseRepository implements ILeaveBalanceRepository
{
    public function __construct(LeaveBalance $model)
    {
        parent::__construct($model);
    }
}
