<?php

namespace App\Repositories\Attendance;

use App\Contracts\Attendance\IAttendanceRepository;
use App\Repositories\BaseRepository;
use App\Models\Attendance\Attendance;

class AttendanceRepository extends BaseRepository implements IAttendanceRepository
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }
}
