<?php

namespace App\Repositories\Timesheet;

use App\Contracts\Timesheet\ITimesheetRepository;
use App\Repositories\BaseRepository;
use App\Models\Timesheet\Timesheet;

class TimesheetRepository extends BaseRepository implements ITimesheetRepository
{
    public function __construct(Timesheet $model)
    {
        parent::__construct($model);
    }
}
