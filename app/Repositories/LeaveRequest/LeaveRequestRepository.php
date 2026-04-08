<?php

namespace App\Repositories\LeaveRequest;

use App\Contracts\LeaveRequest\ILeaveRequestRepository;
use App\Repositories\BaseRepository;
use App\Models\LeaveRequest\LeaveRequest;

class LeaveRequestRepository extends BaseRepository implements ILeaveRequestRepository
{
    public function __construct(LeaveRequest $model)
    {
        parent::__construct($model);
    }
}
