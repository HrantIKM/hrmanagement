<?php

namespace App\Services\Holiday;

use App\Contracts\Holiday\IHolidayRepository;
use App\Services\BaseService;

class HolidayService extends BaseService
{
    public function __construct(
        IHolidayRepository $repository
    ) {
        $this->repository = $repository;
    }
}
