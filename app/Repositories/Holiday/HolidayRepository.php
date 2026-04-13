<?php

namespace App\Repositories\Holiday;

use App\Contracts\Holiday\IHolidayRepository;
use App\Repositories\BaseRepository;
use App\Models\Holiday\Holiday;

class HolidayRepository extends BaseRepository implements IHolidayRepository
{
    public function __construct(Holiday $model)
    {
        parent::__construct($model);
    }
}
